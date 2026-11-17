<?php
declare(strict_types=1);

/** Usuario en sesión, control de acceso y límite de intentos de login. */
final class Auth
{
    private static array|null|false $usuario = false;

    public static function usuario(): ?array
    {
        if (self::$usuario === false) {
            self::$usuario = isset($_SESSION['uid']) ? Usuario::publico((int) $_SESSION['uid']) : null;
            if (self::$usuario === null) {
                unset($_SESSION['uid']);
            }
        }
        return self::$usuario;
    }

    public static function esAdmin(): bool
    {
        return (self::usuario()['rol'] ?? '') === 'admin';
    }

    public static function iniciar(int $idUsuario): void
    {
        session_regenerate_id(true);
        $_SESSION['uid'] = $idUsuario;
        self::$usuario = false;
    }

    public static function cerrar(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'] ?? '', (bool) $p['secure'], (bool) $p['httponly']);
        }
        session_destroy();
        self::$usuario = false;
    }

    public static function requerirLogin(): array
    {
        $u = self::usuario();
        if (!$u) {
            Sesion::flash('info', 'Inicia sesión para continuar.');
            redirigir('login.php?volver=' . urlencode(RUTA_ACTUAL));
        }
        return $u;
    }

    public static function requerirAdmin(): array
    {
        $u = self::usuario();
        if (!$u) {
            redirigir('login.php?volver=' . urlencode(RUTA_ACTUAL));
        }
        if ($u['rol'] !== 'admin') {
            Sesion::flash('error', 'Esa zona es solo para el dueño de la tienda.');
            redirigir('principal.php');
        }
        return $u;
    }

    /** Máximo 6 fallos cada 10 minutos por sesión. */
    public static function bloqueado(): bool
    {
        $intentos = array_filter($_SESSION['_intentos'] ?? [], fn($t) => $t > time() - 600);
        $_SESSION['_intentos'] = $intentos;
        return count($intentos) >= 6;
    }

    public static function intentoFallido(): void
    {
        $_SESSION['_intentos'][] = time();
        usleep(400000); // frena ataques de fuerza bruta
    }

    /** Solo permite volver a páginas internas (evita redirecciones abiertas). */
    public static function destinoSeguro(?string $destino, string $respaldo = 'principal.php'): string
    {
        $d = trim((string) $destino);
        return preg_match('/^[a-z0-9_\-\/]+\.php(\?[a-z0-9_=&%\-]*)?$/i', $d) ? $d : $respaldo;
    }
}
