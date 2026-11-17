<?php
declare(strict_types=1);

/** Categorías del catálogo. */
final class Categoria extends Modelo
{
    /** Categorías visibles en la tienda, con su número de prendas activas. */
    public static function activas(): array
    {
        return self::filas("SELECT c.*, (SELECT COUNT(*) FROM productos p WHERE p.categoria_id = c.id AND p.activo = 1) AS total
                            FROM categorias c WHERE c.activa = 1 ORDER BY c.orden, c.nombre");
    }

    /** Todas (también ocultas) con el total de prendas, para el panel. */
    public static function todasConTotal(): array
    {
        return self::filas('SELECT c.*, (SELECT COUNT(*) FROM productos p WHERE p.categoria_id = c.id) AS total FROM categorias c ORDER BY c.orden, c.nombre');
    }

    /** Lista simple para selects (formulario de prenda). */
    public static function todas(): array
    {
        return self::filas('SELECT id, nombre FROM categorias ORDER BY orden, nombre');
    }

    /** Nombre y slug de las visibles (atajos de enlace en banners). */
    public static function atajos(): array
    {
        return self::filas('SELECT nombre, slug FROM categorias WHERE activa = 1 ORDER BY orden, nombre');
    }

    public static function existe(int $id): bool
    {
        return self::fila('SELECT id FROM categorias WHERE id = ?', [$id]) !== null;
    }

    public static function nombre(int $id): ?string
    {
        $n = self::valor('SELECT nombre FROM categorias WHERE id = ?', [$id]);
        return $n === null ? null : (string) $n;
    }

    public static function icono(int $id): ?string
    {
        $i = self::valor('SELECT icono FROM categorias WHERE id = ?', [$id]);
        return $i === null ? null : (string) $i;
    }

    public static function crear(string $nombre, string $icono, int $orden): void
    {
        self::consulta('INSERT INTO categorias (nombre, slug, icono, orden, activa) VALUES (?,?,?,?,1)',
            [$nombre, self::slugUnico('categorias', $nombre), $icono, $orden]);
    }

    public static function actualizar(int $id, string $nombre, string $icono, int $orden, bool $activa): void
    {
        self::consulta('UPDATE categorias SET nombre = ?, icono = ?, orden = ?, activa = ? WHERE id = ?', [$nombre, $icono, $orden, $activa ? 1 : 0, $id]);
    }

    /** Sus prendas no se borran: quedan "sin categoría". */
    public static function eliminar(int $id): void
    {
        self::consulta('DELETE FROM categorias WHERE id = ?', [$id]);
    }
}
