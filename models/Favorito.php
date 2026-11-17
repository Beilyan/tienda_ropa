<?php
declare(strict_types=1);

/** Prendas favoritas de cada cliente. */
final class Favorito extends Modelo
{
    private static ?array $ids = null;

    /** IDs de las prendas favoritas del usuario en sesión. */
    public static function idsDelUsuario(): array
    {
        if (self::$ids === null) {
            $u = Auth::usuario();
            self::$ids = $u ? array_map('intval', array_column(
                self::filas('SELECT producto_id FROM favoritos WHERE usuario_id = ?', [$u['id']]), 'producto_id')) : [];
        }
        return self::$ids;
    }

    public static function contar(int $usuarioId): int
    {
        return count(self::idsDelUsuario());
    }

    /** Guarda o quita. Devuelve [existeLaPrenda, quedóComoFavorita]. */
    public static function alternar(int $usuarioId, int $productoId): array
    {
        if ((int) self::valor('SELECT COUNT(*) FROM productos WHERE id = ?', [$productoId]) === 0) {
            return [false, false];
        }
        $ya = (int) self::valor('SELECT COUNT(*) FROM favoritos WHERE usuario_id = ? AND producto_id = ?', [$usuarioId, $productoId]) > 0;
        if ($ya) {
            self::consulta('DELETE FROM favoritos WHERE usuario_id = ? AND producto_id = ?', [$usuarioId, $productoId]);
        } else {
            self::consulta('INSERT INTO favoritos (usuario_id, producto_id) VALUES (?, ?)', [$usuarioId, $productoId]);
        }
        return [true, !$ya];
    }
}
