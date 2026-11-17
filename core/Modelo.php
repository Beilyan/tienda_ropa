<?php
declare(strict_types=1);

/** Base de los modelos: atajos a la base de datos y generador de slugs. */
abstract class Modelo
{
    protected static function consulta(string $sql, array $p = []): PDOStatement { return Database::consulta($sql, $p); }
    protected static function fila(string $sql, array $p = []): ?array { return Database::fila($sql, $p); }
    protected static function filas(string $sql, array $p = []): array { return Database::filas($sql, $p); }
    protected static function valor(string $sql, array $p = []): mixed { return Database::valor($sql, $p); }

    /** Slug único dentro de una tabla (productos / categorias). */
    protected static function slugUnico(string $tabla, string $texto, int $ignorarId = 0): string
    {
        if (!in_array($tabla, ['productos', 'categorias'], true)) {
            throw new InvalidArgumentException('Tabla no permitida');
        }
        $base = slugify($texto);
        $slug = $base;
        $n = 2;
        while ((int) self::valor("SELECT COUNT(*) FROM $tabla WHERE slug = ? AND id <> ?", [$slug, $ignorarId]) > 0) {
            $slug = $base . '-' . $n++;
        }
        return $slug;
    }
}
