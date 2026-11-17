<?php
declare(strict_types=1);

/** MI PERFIL: datos personales y cambio de contraseña. */
final class PerfilController extends Controller
{
    public function index(): void
    {
        $u = Auth::usuario();
        $errores = [];
        $v = ['nombre' => $u['nombre'], 'email' => $u['email'], 'telefono' => (string) $u['telefono']];

        if ($this->esPost()) {
            $accion = $_POST['accion'] ?? '';
            if ($accion === 'datos') {
                foreach ($v as $k => $_) {
                    $v[$k] = $this->texto($_POST, $k);
                }
                $v['email'] = mb_strtolower($v['email']);
                if (mb_strlen($v['nombre']) < 3) $errores['nombre'] = 'Escribe tu nombre.';
                if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errores['email'] = 'Escribe un correo válido.';
                elseif (Usuario::emailEnUso($v['email'], (int) $u['id'])) $errores['email'] = 'Ese correo ya está en uso.';
                if ($v['telefono'] !== '' && strlen(solo_digitos($v['telefono'])) < 8) $errores['telefono'] = 'Revisa tu teléfono.';
                if (!$errores) {
                    Usuario::actualizarDatos((int) $u['id'], $v['nombre'], $v['email'], $v['telefono'] ?: null);
                    $this->flash('ok', 'Guardamos tus datos.');
                    $this->redirigir('perfil.php');
                }
            } elseif ($accion === 'clave') {
                $nueva = (string) ($_POST['nueva'] ?? '');
                if (!password_verify((string) ($_POST['actual'] ?? ''), Usuario::hashClave((int) $u['id']))) $errores['actual'] = 'Tu contraseña actual no coincide.';
                if (mb_strlen($nueva) < 8) $errores['nueva'] = 'La nueva contraseña debe tener al menos 8 caracteres.';
                if ($nueva !== (string) ($_POST['nueva2'] ?? '')) $errores['nueva2'] = 'Las contraseñas no coinciden.';
                if (!$errores) {
                    Usuario::cambiarClave((int) $u['id'], $nueva);
                    session_regenerate_id(true);
                    $this->flash('ok', 'Cambiaste tu contraseña.');
                    $this->redirigir('perfil.php');
                }
            }
        }

        $this->vista('cuenta/perfil', [
            'titulo' => 'Mi perfil', 'u' => $u, 'v' => $v, 'errores' => $errores,
            'pedidos' => Pedido::contarDelUsuario((int) $u['id']),
            'favs' => Favorito::contar((int) $u['id']),
        ]);
    }
}
