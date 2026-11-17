<?php
declare(strict_types=1);

/** Cifras y avisos del panel del dueño ("Para revisar hoy"). */
final class Resumen extends Modelo
{
    public static function ventasDelMes(): array
    {
        $desde = date('Y-m-01');
        return [
            'ventas'  => (float) self::valor("SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE estado <> 'cancelado' AND creado_en >= ?", [$desde]),
            'pedidos' => (int) self::valor("SELECT COUNT(*) FROM pedidos WHERE estado <> 'cancelado' AND creado_en >= ?", [$desde]),
        ];
    }

    public static function productosActivos(): int
    {
        return (int) self::valor('SELECT COUNT(*) FROM productos WHERE activo = 1');
    }

    public static function agotados(int $limite = 20): array
    {
        return self::filas("SELECT p.id, p.nombre FROM productos p WHERE p.activo = 1 AND " . Producto::SQL_STOCK . " <= 0 ORDER BY p.nombre LIMIT " . $limite);
    }

    public static function stockBajo(int $limite = 20): array
    {
        return self::filas("SELECT p.id, p.nombre, MIN(t.stock) AS minimo FROM productos p JOIN producto_tallas t ON t.producto_id = p.id
                            WHERE p.activo = 1 AND t.stock BETWEEN 1 AND 3 GROUP BY p.id, p.nombre ORDER BY minimo LIMIT " . $limite);
    }

    public static function ofertasPorVencer(): array
    {
        return self::filas("SELECT id, nombre, oferta_hasta FROM productos WHERE activo = 1 AND precio_oferta IS NOT NULL AND precio_oferta < precio
                            AND oferta_hasta BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY oferta_hasta");
    }

    public static function ofertasVencidas(int $limite = 20): array
    {
        return self::filas("SELECT id, nombre, oferta_hasta FROM productos WHERE precio_oferta IS NOT NULL AND oferta_hasta < CURDATE() ORDER BY oferta_hasta DESC LIMIT " . $limite);
    }

    public static function bannersVigentes(): int
    {
        return (int) self::valor("SELECT COUNT(*) FROM banners WHERE activo = 1 AND (inicio IS NULL OR inicio <= CURDATE()) AND (fin IS NULL OR fin >= CURDATE())");
    }

    public static function bannersPorVencer(): int
    {
        return (int) self::valor("SELECT COUNT(*) FROM banners WHERE activo = 1 AND fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
    }

    public static function sinFoto(): int
    {
        return (int) self::valor("SELECT COUNT(*) FROM productos WHERE activo = 1 AND (imagen IS NULL OR imagen = '')");
    }

    public static function ultimosPedidos(int $n = 6): array
    {
        return self::filas('SELECT * FROM pedidos ORDER BY creado_en DESC, id DESC LIMIT ' . $n);
    }
}
