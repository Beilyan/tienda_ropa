<?php
declare(strict_types=1);

/** Cupones de descuento. */
final class Cupon extends Modelo
{
    public static function todos(): array
    {
        return self::filas('SELECT * FROM cupones ORDER BY activo DESC, id DESC');
    }

    public static function porId(int $id): ?array
    {
        return self::fila('SELECT * FROM cupones WHERE id = ?', [$id]);
    }

    public static function existeCodigo(string $codigo, int $ignorarId = 0): bool
    {
        return self::fila('SELECT id FROM cupones WHERE codigo = ? AND id <> ?', [$codigo, $ignorarId]) !== null;
    }

    /** $d: codigo, tipo, valor, minimo_compra, usos_max, inicio, fin, activo */
    public static function crear(array $d): void
    {
        self::consulta('INSERT INTO cupones (codigo, tipo, valor, minimo_compra, usos_max, inicio, fin, activo) VALUES (?,?,?,?,?,?,?,?)', self::valores($d));
    }

    public static function actualizar(int $id, array $d): void
    {
        self::consulta('UPDATE cupones SET codigo=?, tipo=?, valor=?, minimo_compra=?, usos_max=?, inicio=?, fin=?, activo=? WHERE id=?', [...self::valores($d), $id]);
    }

    public static function eliminar(int $id): void
    {
        self::consulta('DELETE FROM cupones WHERE id = ?', [$id]);
    }

    public static function alternar(int $id): void
    {
        self::consulta('UPDATE cupones SET activo = 1 - activo WHERE id = ?', [$id]);
    }

    /** Devuelve ['ok'=>bool, 'mensaje'=>string, 'cupon'=>?array, 'descuento'=>float] */
    public static function validar(string $codigo, float $subtotal): array
    {
        $codigo = strtoupper(trim($codigo));
        $c = $codigo === '' ? null : self::fila('SELECT * FROM cupones WHERE codigo = ?', [$codigo]);
        $fallo = fn(string $m) => ['ok' => false, 'mensaje' => $m, 'cupon' => null, 'descuento' => 0.0];
        if (!$c || !(int) $c['activo']) {
            return $fallo('Ese cupón no existe o ya no está activo.');
        }
        if ($c['inicio'] && $c['inicio'] > hoy()) {
            return $fallo('Ese cupón todavía no está vigente.');
        }
        if ($c['fin'] && $c['fin'] < hoy()) {
            return $fallo('Ese cupón ya venció.');
        }
        if ($c['usos_max'] !== null && (int) $c['usos'] >= (int) $c['usos_max']) {
            return $fallo('Ese cupón ya alcanzó su límite de usos.');
        }
        if ($subtotal < (float) $c['minimo_compra']) {
            return $fallo('Este cupón aplica en compras desde ' . dinero($c['minimo_compra']) . '.');
        }
        $desc = $c['tipo'] === 'porcentaje'
            ? $subtotal * min(100.0, (float) $c['valor']) / 100
            : min((float) $c['valor'], $subtotal);
        return ['ok' => true, 'mensaje' => 'Cupón aplicado.', 'cupon' => $c, 'descuento' => round($desc, 2)];
    }

    /** Bloquea la fila del cupón dentro de una transacción. */
    public static function bloquear(string $codigo): void
    {
        self::valor('SELECT id FROM cupones WHERE codigo = ? FOR UPDATE', [$codigo]);
    }

    public static function sumarUso(string $codigo): void
    {
        self::consulta('UPDATE cupones SET usos = usos + 1 WHERE codigo = ?', [$codigo]);
    }

    public static function restarUso(string $codigo): void
    {
        self::consulta('UPDATE cupones SET usos = GREATEST(usos - 1, 0) WHERE codigo = ?', [$codigo]);
    }

    private static function valores(array $d): array
    {
        return [$d['codigo'], $d['tipo'], $d['valor'], $d['minimo_compra'], $d['usos_max'], $d['inicio'], $d['fin'], $d['activo']];
    }
}
