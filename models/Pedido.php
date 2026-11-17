<?php
declare(strict_types=1);

/** Pedidos de clientes y su seguimiento. */
final class Pedido extends Modelo
{
    /**
     * Crea el pedido, descuenta el stock y usa el cupón dentro de una transacción:
     * si algo falla no se guarda nada a medias. Devuelve el código del pedido.
     * @throws RuntimeException con un mensaje para el cliente (stock o cupón)
     */
    public static function crear(array $d, array $t, ?array $usuario): string
    {
        return (string) Database::transaccion(function () use ($d, $t, $usuario): string {
            // 1) Bloquea y verifica el stock de cada prenda
            foreach ($t['lineas'] as $l) {
                $stock = self::valor('SELECT stock FROM producto_tallas WHERE producto_id = ? AND talla = ? FOR UPDATE', [$l['id'], $l['talla']]);
                if ($stock === null || (int) $stock < (int) $l['cantidad']) {
                    throw new RuntimeException('«' . $l['nombre'] . '» (talla ' . $l['talla'] . ') ya no tiene suficientes piezas. Revisa tu carrito.');
                }
            }
            // 2) Revalida y consume el cupón
            $descuento = 0.0;
            $cuponCodigo = null;
            if (!empty($t['cupon'])) {
                Cupon::bloquear($t['cupon']['codigo']);
                $v = Cupon::validar($t['cupon']['codigo'], $t['subtotal']);
                if (!$v['ok']) {
                    throw new RuntimeException($v['mensaje']);
                }
                $descuento = $v['descuento'];
                $cuponCodigo = $v['cupon']['codigo'];
                Cupon::sumarUso($cuponCodigo);
            }
            $total = round($t['subtotal'] - $descuento + $t['envio'], 2);

            // 3) Crea el pedido con un código legible y único
            do {
                $codigo = 'P' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
            } while ((int) self::valor('SELECT COUNT(*) FROM pedidos WHERE codigo = ?', [$codigo]) > 0);

            self::consulta('INSERT INTO pedidos (codigo, usuario_id, nombre, email, telefono, direccion, ciudad, cp, notas, metodo_pago, subtotal, descuento, envio, total, cupon_codigo)
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                [$codigo, $usuario['id'] ?? null, $d['nombre'], $d['email'], $d['telefono'], $d['direccion'], $d['ciudad'], $d['cp'],
                 $d['notas'] !== '' ? $d['notas'] : null, $d['metodo'], $t['subtotal'], $descuento, $t['envio'], $total, $cuponCodigo]);
            $idPedido = Database::ultimoId();

            foreach ($t['lineas'] as $l) {
                self::consulta('INSERT INTO pedido_items (pedido_id, producto_id, nombre, talla, precio, cantidad) VALUES (?,?,?,?,?,?)',
                    [$idPedido, $l['id'], $l['nombre'], $l['talla'], $l['precio_final'], $l['cantidad']]);
                self::consulta('UPDATE producto_tallas SET stock = stock - ? WHERE producto_id = ? AND talla = ?', [$l['cantidad'], $l['id'], $l['talla']]);
            }
            return $codigo;
        });
    }

    public static function porCodigo(string $codigo): ?array
    {
        return $codigo === '' ? null : self::fila('SELECT * FROM pedidos WHERE codigo = ?', [$codigo]);
    }

    public static function porId(int $id): ?array
    {
        return self::fila('SELECT * FROM pedidos WHERE id = ?', [$id]);
    }

    public static function items(int $idPedido): array
    {
        return self::filas('SELECT * FROM pedido_items WHERE pedido_id = ? ORDER BY id', [$idPedido]);
    }

    /** Historial de un cliente, cada pedido con sus prendas en la clave "items". */
    public static function delUsuario(int $usuarioId): array
    {
        $pedidos = self::filas('SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY creado_en DESC, id DESC', [$usuarioId]);
        foreach ($pedidos as &$p) {
            $p['items'] = self::filas('SELECT * FROM pedido_items WHERE pedido_id = ?', [$p['id']]);
        }
        return $pedidos;
    }

    public static function contarDelUsuario(int $usuarioId): int
    {
        return (int) self::valor('SELECT COUNT(*) FROM pedidos WHERE usuario_id = ?', [$usuarioId]);
    }

    /* ---------- Panel del dueño ---------- */

    public static function contarPendientes(): int
    {
        return (int) self::valor("SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente'");
    }

    private static function condicion(string $estado, string $q): array
    {
        $cond = ' WHERE 1=1';
        $params = [];
        if ($estado !== '') {
            $cond .= ' AND estado = ?';
            $params[] = $estado;
        }
        if ($q !== '') {
            $cond .= ' AND (codigo LIKE ? OR nombre LIKE ? OR email LIKE ? OR telefono LIKE ?)';
            $like = '%' . addcslashes($q, '%_\\') . '%';
            array_push($params, $like, $like, $like, $like);
        }
        return [$cond, $params];
    }

    public static function contar(string $estado = '', string $q = ''): int
    {
        [$cond, $params] = self::condicion($estado, $q);
        return (int) self::valor('SELECT COUNT(*) FROM pedidos' . $cond, $params);
    }

    public static function listar(string $estado, string $q, int $limite, int $desde): array
    {
        [$cond, $params] = self::condicion($estado, $q);
        return self::filas('SELECT * FROM pedidos' . $cond . ' ORDER BY creado_en DESC, id DESC LIMIT ' . max(1, $limite) . ' OFFSET ' . max(0, $desde), $params);
    }

    public static function conteosPorEstado(): array
    {
        return array_column(self::filas('SELECT estado, COUNT(*) AS n FROM pedidos GROUP BY estado'), 'n', 'estado');
    }

    /**
     * Cambia el estado. Al cancelar devuelve las piezas al inventario y libera el uso del
     * cupón; un pedido cancelado es definitivo. Lanza RuntimeException si ya estaba cancelado.
     */
    public static function cambiarEstado(array $pedido, string $nuevo): void
    {
        $id = (int) $pedido['id'];
        Database::transaccion(function () use ($pedido, $nuevo, $id): void {
            if (self::valor('SELECT estado FROM pedidos WHERE id = ? FOR UPDATE', [$id]) === 'cancelado') {
                throw new RuntimeException('cancelado');
            }
            if ($nuevo === 'cancelado') {
                foreach (self::filas('SELECT producto_id, talla, cantidad FROM pedido_items WHERE pedido_id = ? AND producto_id IS NOT NULL', [$id]) as $it) {
                    self::consulta('UPDATE producto_tallas SET stock = stock + ? WHERE producto_id = ? AND talla = ?', [$it['cantidad'], $it['producto_id'], $it['talla']]);
                }
                if ($pedido['cupon_codigo']) {
                    Cupon::restarUso($pedido['cupon_codigo']);
                }
            }
            self::consulta('UPDATE pedidos SET estado = ? WHERE id = ?', [$nuevo, $id]);
        });
    }
}
