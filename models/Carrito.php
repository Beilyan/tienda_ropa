<?php
declare(strict_types=1);

/**
 * Carrito de compras (guardado en la sesión del visitante).
 * Los precios y el stock se leen SIEMPRE de la base de datos.
 */
final class Carrito extends Modelo
{
    public static function clave(int $idProducto, string $talla): string
    {
        return $idProducto . '|' . $talla;
    }

    public static function claveValida(string $clave): bool
    {
        return (bool) preg_match('/^\d+\|[^|]{1,10}$/', $clave);
    }

    /** Agrega una prenda. Devuelve un mensaje de aviso/error o null si todo salió bien. */
    public static function agregar(int $idProducto, string $talla, int $cantidad): ?string
    {
        $cantidad = max(1, min($cantidad, 20));
        $fila = self::fila("SELECT t.stock FROM producto_tallas t JOIN productos p ON p.id = t.producto_id
                            WHERE p.id = ? AND p.activo = 1 AND t.talla = ?", [$idProducto, $talla]);
        if (!$fila || (int) $fila['stock'] <= 0) {
            return 'Esa talla no está disponible por ahora.';
        }
        $clave = self::clave($idProducto, $talla);
        $actual = (int) ($_SESSION['carrito'][$clave] ?? 0);
        $_SESSION['carrito'][$clave] = min($actual + $cantidad, (int) $fila['stock']);
        if ($actual + $cantidad > (int) $fila['stock']) {
            return 'Solo quedan ' . (int) $fila['stock'] . ' piezas en esa talla; ajustamos tu carrito.';
        }
        return null;
    }

    /** Cambia la cantidad de una línea (0 o menos la quita). detalle() la ajusta al stock real. */
    public static function actualizar(string $clave, int $cantidad): void
    {
        if (!self::claveValida($clave) || !isset($_SESSION['carrito'][$clave])) {
            return;
        }
        if ($cantidad <= 0) {
            unset($_SESSION['carrito'][$clave]);
        } else {
            $_SESSION['carrito'][$clave] = min($cantidad, 20);
        }
    }

    public static function quitar(string $clave): bool
    {
        if (!self::claveValida($clave)) {
            return false;
        }
        unset($_SESSION['carrito'][$clave]);
        return true;
    }

    public static function vaciar(): void
    {
        unset($_SESSION['carrito'], $_SESSION['cupon']);
    }

    public static function unidades(): int
    {
        return array_sum(array_map('intval', $_SESSION['carrito'] ?? []));
    }

    /**
     * Lee el carrito con datos frescos de la base: precios vigentes y stock real.
     * Quita lo que ya no existe o está agotado y avisa al cliente.
     */
    public static function detalle(): array
    {
        $lineas = [];
        $subtotal = 0.0;
        $unidades = 0;
        $avisos = [];
        foreach (($_SESSION['carrito'] ?? []) as $clave => $cant) {
            [$id, $talla] = array_pad(explode('|', (string) $clave, 2), 2, '');
            $p = self::fila("SELECT p.id, p.nombre, p.imagen, p.color_hex, c.icono AS icono, p.precio,
                                    " . Producto::SQL_PRECIO_FINAL . " AS precio_final, " . Producto::SQL_EN_OFERTA . " AS en_oferta, t.stock
                             FROM productos p
                             JOIN producto_tallas t ON t.producto_id = p.id AND t.talla = ?
                             LEFT JOIN categorias c ON c.id = p.categoria_id
                             WHERE p.id = ? AND p.activo = 1", [$talla, (int) $id]);
            if (!$p || (int) $p['stock'] <= 0) {
                unset($_SESSION['carrito'][$clave]);
                $avisos[] = 'Quitamos del carrito una prenda que ya no está disponible.';
                continue;
            }
            $cant = (int) $cant;
            if ($cant > (int) $p['stock']) {
                $cant = (int) $p['stock'];
                $_SESSION['carrito'][$clave] = $cant;
                $avisos[] = 'Ajustamos la cantidad de «' . $p['nombre'] . '» al stock disponible.';
            }
            $linea = $p + ['clave' => $clave, 'talla' => $talla, 'cantidad' => $cant, 'importe' => $cant * (float) $p['precio_final']];
            $lineas[] = $linea;
            $subtotal += $linea['importe'];
            $unidades += $cant;
        }
        return ['lineas' => $lineas, 'subtotal' => round($subtotal, 2), 'unidades' => $unidades, 'avisos' => array_unique($avisos)];
    }

    /** Totales completos (subtotal, descuento, envío y total). */
    public static function totales(?array $detalle = null): array
    {
        $d = $detalle ?? self::detalle();
        $descuento = 0.0;
        $cupon = null;
        if (!empty($_SESSION['cupon'])) {
            $v = Cupon::validar((string) $_SESSION['cupon'], $d['subtotal']);
            if ($v['ok']) {
                $descuento = $v['descuento'];
                $cupon = $v['cupon'];
            } else {
                unset($_SESSION['cupon']);
                $d['avisos'][] = $v['mensaje'];
            }
        }
        $gratisDesde = (float) aj('costo_envio') > 0 ? (float) aj('envio_gratis_desde') : 0.0;
        $envio = 0.0;
        if ($d['subtotal'] > 0 && (float) aj('costo_envio') > 0 && !($gratisDesde > 0 && $d['subtotal'] >= $gratisDesde)) {
            $envio = (float) aj('costo_envio');
        }
        return $d + [
            'descuento' => $descuento,
            'cupon' => $cupon,
            'envio' => $envio,
            'total' => round($d['subtotal'] - $descuento + $envio, 2),
            'falta_gratis' => ($gratisDesde > 0 && $d['subtotal'] > 0 && $d['subtotal'] < $gratisDesde) ? $gratisDesde - $d['subtotal'] : 0.0,
        ];
    }

    /** Intenta aplicar un cupón. Devuelve ['ok'=>bool, 'mensaje'=>string, 'codigo'=>?string]. */
    public static function aplicarCupon(string $codigo): array
    {
        $v = Cupon::validar($codigo, self::detalle()['subtotal']);
        if ($v['ok']) {
            $_SESSION['cupon'] = $v['cupon']['codigo'];
        }
        return ['ok' => $v['ok'], 'mensaje' => $v['mensaje'], 'codigo' => $v['cupon']['codigo'] ?? null];
    }

    public static function quitarCupon(): void
    {
        unset($_SESSION['cupon']);
    }
}
