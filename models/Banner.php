<?php
declare(strict_types=1);

/** Banners del inicio (con fechas de inicio y fin opcionales). */
final class Banner extends Modelo
{
    /** Los que se ven hoy: activos y dentro de sus fechas. */
    public static function vigentes(): array
    {
        return self::filas("SELECT * FROM banners
                            WHERE activo = 1 AND (inicio IS NULL OR inicio <= CURDATE()) AND (fin IS NULL OR fin >= CURDATE())
                            ORDER BY orden, id");
    }

    public static function todos(): array
    {
        return self::filas('SELECT * FROM banners ORDER BY orden, id');
    }

    public static function porId(int $id): ?array
    {
        return self::fila('SELECT * FROM banners WHERE id = ?', [$id]);
    }

    /** $d: etiqueta, titulo, titulo_cursiva, texto, boton_texto, boton_enlace, color_fondo, imagen, activo, orden, inicio, fin */
    public static function crear(array $d): void
    {
        self::consulta('INSERT INTO banners (etiqueta, titulo, titulo_cursiva, texto, boton_texto, boton_enlace, color_fondo, imagen, activo, orden, inicio, fin) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)',
            self::valores($d));
    }

    public static function actualizar(int $id, array $d): void
    {
        self::consulta('UPDATE banners SET etiqueta=?, titulo=?, titulo_cursiva=?, texto=?, boton_texto=?, boton_enlace=?, color_fondo=?, imagen=?, activo=?, orden=?, inicio=?, fin=? WHERE id=?',
            [...self::valores($d), $id]);
    }

    public static function eliminar(int $id): void
    {
        self::consulta('DELETE FROM banners WHERE id = ?', [$id]);
    }

    public static function alternar(int $id): void
    {
        self::consulta('UPDATE banners SET activo = 1 - activo WHERE id = ?', [$id]);
    }

    private static function valores(array $d): array
    {
        return [$d['etiqueta'], $d['titulo'], $d['titulo_cursiva'], $d['texto'], $d['boton_texto'], $d['boton_enlace'],
                $d['color_fondo'], $d['imagen'], $d['activo'], $d['orden'], $d['inicio'], $d['fin']];
    }
}
