<?php
declare(strict_types=1);

/** Clientes y dueño (tabla "usuarios"). */
final class Usuario extends Modelo
{
    /** Datos que se pueden mostrar (sin contraseña). */
    public static function publico(int $id): ?array
    {
        return self::fila('SELECT id, nombre, email, telefono, rol FROM usuarios WHERE id = ?', [$id]);
    }

    /** Para iniciar sesión: id, hash y rol. */
    public static function credenciales(string $email): ?array
    {
        return self::fila('SELECT id, password_hash, rol FROM usuarios WHERE email = ?', [$email]);
    }

    public static function hashClave(int $id): string
    {
        return (string) self::valor('SELECT password_hash FROM usuarios WHERE id = ?', [$id]);
    }

    public static function emailEnUso(string $email, int $ignorarId = 0): bool
    {
        return (int) self::valor('SELECT COUNT(*) FROM usuarios WHERE email = ? AND id <> ?', [$email, $ignorarId]) > 0;
    }

    public static function crear(string $nombre, string $email, string $clave, ?string $telefono, string $rol = 'cliente'): int
    {
        self::consulta('INSERT INTO usuarios (nombre, email, password_hash, telefono, rol) VALUES (?,?,?,?,?)',
            [$nombre, $email, password_hash($clave, PASSWORD_DEFAULT), $telefono, $rol]);
        return Database::ultimoId();
    }

    public static function actualizarDatos(int $id, string $nombre, string $email, ?string $telefono): void
    {
        self::consulta('UPDATE usuarios SET nombre = ?, email = ?, telefono = ? WHERE id = ?', [$nombre, $email, $telefono, $id]);
    }

    public static function cambiarClave(int $id, string $clave): void
    {
        self::consulta('UPDATE usuarios SET password_hash = ? WHERE id = ?', [password_hash($clave, PASSWORD_DEFAULT), $id]);
    }

    /** Cuenta del dueño (panel > Ajustes): nombre, correo y, si se indica, contraseña nueva. */
    public static function actualizarCuenta(int $id, string $nombre, string $email, ?string $claveNueva): void
    {
        if ($claveNueva !== null && $claveNueva !== '') {
            self::consulta('UPDATE usuarios SET nombre = ?, email = ?, password_hash = ? WHERE id = ?',
                [$nombre, $email, password_hash($claveNueva, PASSWORD_DEFAULT), $id]);
        } else {
            self::consulta('UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?', [$nombre, $email, $id]);
        }
    }
}
