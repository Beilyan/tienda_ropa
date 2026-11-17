<?php
declare(strict_types=1);

/** ACCESO DE CLIENTES: iniciar sesión, crear cuenta y cerrar sesión. */
final class AuthController extends Controller
{
    public function login(): void
    {
        $volver = Auth::destinoSeguro($_GET['volver'] ?? $_POST['volver'] ?? '');
        if (Auth::usuario()) {
            $this->redirigir($this->destinoTrasEntrar(Auth::usuario(), $volver));
        }

        $error = '';
        $email = '';
        if ($this->esPost()) {
            $email = mb_strtolower($this->texto($_POST, 'email'));
            if (Auth::bloqueado()) {
                $error = 'Demasiados intentos. Espera unos minutos e inténtalo de nuevo.';
            } else {
                $u = Usuario::credenciales($email);
                // password_verify también se ejecuta si el usuario no existe, para no revelar qué correos están registrados
                $hash = $u['password_hash'] ?? password_hash('relleno', PASSWORD_DEFAULT);
                if ($u && password_verify((string) ($_POST['clave'] ?? ''), $hash)) {
                    Auth::iniciar((int) $u['id']);
                    $this->redirigir($this->destinoTrasEntrar($u, $volver));
                }
                Auth::intentoFallido();
                $error = 'El correo o la contraseña no coinciden.';
            }
        }

        $this->vista('auth/login', [
            'titulo' => 'Iniciar sesión', 'etiqueta' => 'BIENVENIDO DE NUEVO', 'linea1' => 'Viste como', 'linea2' => 'quieres sentirte.',
            'texto' => 'Guarda tus favoritos y consulta el estado de tus pedidos.',
            'volver' => $volver, 'error' => $error, 'email' => $email, 'mensajes' => Sesion::leerFlashes(),
        ], 'auth');
    }

    /** Una sola entrada para todos: el dueño llega a su panel, el cliente a la tienda. */
    private function destinoTrasEntrar(array $u, string $volver): string
    {
        if (($u['rol'] ?? '') === 'admin' && !str_starts_with($volver, 'admin/')) {
            return 'admin/index.php';
        }
        return $volver;
    }

    /** Dirección antigua del acceso al panel: ahora se entra por el inicio de sesión de siempre. */
    public function entradaDueno(): void
    {
        $this->redirigir('login.php?volver=admin/index.php');
    }

    public function registro(): void
    {
        $volver = Auth::destinoSeguro($_GET['volver'] ?? $_POST['volver'] ?? '');
        if (Auth::usuario()) {
            $this->redirigir($volver);
        }

        $v = ['nombre' => '', 'email' => '', 'telefono' => ''];
        $errores = [];
        if ($this->esPost()) {
            foreach ($v as $k => $_) {
                $v[$k] = $this->texto($_POST, $k);
            }
            $v['email'] = mb_strtolower($v['email']);
            $clave = (string) ($_POST['clave'] ?? '');
            if (mb_strlen($v['nombre']) < 3) $errores['nombre'] = 'Escribe tu nombre.';
            if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errores['email'] = 'Escribe un correo válido.';
            elseif (Usuario::emailEnUso($v['email'])) $errores['email'] = 'Ya existe una cuenta con ese correo. Inicia sesión.';
            if ($v['telefono'] !== '' && strlen(solo_digitos($v['telefono'])) < 8) $errores['telefono'] = 'Revisa tu teléfono.';
            if (mb_strlen($clave) < 8) $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
            if ($clave !== (string) ($_POST['clave2'] ?? '')) $errores['clave2'] = 'Las contraseñas no coinciden.';

            if (!$errores) {
                $id = Usuario::crear($v['nombre'], $v['email'], $clave, $v['telefono'] !== '' ? $v['telefono'] : null);
                Auth::iniciar($id);
                $this->flash('ok', '¡Cuenta creada! Ya puedes guardar favoritos y comprar más rápido.');
                $this->redirigir($volver);
            }
        }

        $this->vista('auth/registro', [
            'titulo' => 'Crear cuenta', 'etiqueta' => 'ÚNETE', 'linea1' => 'Tu estilo,', 'linea2' => 'tu cuenta.',
            'texto' => 'Guarda tus prendas favoritas, repite compras y sigue tus pedidos.',
            'volver' => $volver, 'v' => $v, 'errores' => $errores,
        ], 'auth');
    }

    /** Cierra la sesión (solo por POST con token, para que nadie pueda cerrarla desde otro sitio). */
    public function logout(): void
    {
        if ($this->esPost()) {
            Auth::cerrar();
            session_start(); // nueva sesión limpia para mostrar el mensaje
            $this->flash('info', 'Cerraste sesión. ¡Hasta pronto!');
        }
        $this->redirigir('principal.php');
    }
}
