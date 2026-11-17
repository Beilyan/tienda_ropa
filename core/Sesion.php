<?php
declare(strict_types=1);

/** Sesión, mensajes flash y protección CSRF. */
final class Sesion
{
    public static function iniciar(): void
    {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: same-origin');
        session_name((string) Config::get('app.sesion'));
        session_set_cookie_params([
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        ]);
        session_start();
    }

    /** Tipos: ok | error | info */
    public static function flash(string $tipo, string $mensaje): void
    {
        $_SESSION['_flash'][] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    }

    public static function leerFlashes(): array
    {
        $f = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $f;
    }

    public static function csrfToken(): string
    {
        return $_SESSION['_csrf'] ??= bin2hex(random_bytes(32));
    }

    public static function verificarCsrf(): void
    {
        if (!hash_equals(self::csrfToken(), (string) ($_POST['_csrf'] ?? ''))) {
            pagina_error('El formulario expiró o no es válido. Vuelve atrás, recarga la página e inténtalo de nuevo.', 419);
        }
    }
}
