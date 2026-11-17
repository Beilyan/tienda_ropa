<?php
declare(strict_types=1);

/** AJUSTES: datos de la tienda, envíos, pagos, textos y cuenta del dueño. */
final class AdminAjusteController extends AdminController
{
    private const CAMPOS = ['nombre_tienda', 'eslogan', 'color_marca', 'whatsapp', 'email_contacto', 'telefono', 'direccion', 'horario', 'instagram', 'facebook',
                            'costo_envio', 'envio_gratis_desde', 'datos_transferencia', 'pie_texto',
                            'beneficio1_titulo', 'beneficio1_texto', 'beneficio2_titulo', 'beneficio2_texto', 'beneficio3_titulo', 'beneficio3_texto'];

    public function index(): void
    {
        $admin = Auth::usuario();
        $errores = [];
        $v = [];
        foreach (self::CAMPOS as $k) $v[$k] = aj($k);
        $pagos = ['pago_contra_entrega' => aj('pago_contra_entrega') === '1', 'pago_transferencia' => aj('pago_transferencia') === '1', 'pago_whatsapp' => aj('pago_whatsapp') === '1'];
        $cuenta = ['nombre' => $admin['nombre'], 'email' => $admin['email']];

        if ($this->esPost()) {
            $accion = (string) ($_POST['accion'] ?? '');

            if ($accion === 'tienda') {
                foreach (self::CAMPOS as $k) $v[$k] = $this->texto($_POST, $k);
                foreach ($pagos as $k => $_) $pagos[$k] = isset($_POST[$k]);

                if (mb_strlen($v['nombre_tienda']) < 2) $errores['nombre_tienda'] = 'Escribe el nombre de tu tienda.';
                if (!preg_match('/^#[0-9a-fA-F]{6}$/', $v['color_marca'])) $errores['color_marca'] = 'Elige un color válido.';
                if ($v['email_contacto'] !== '' && !filter_var($v['email_contacto'], FILTER_VALIDATE_EMAIL)) $errores['email_contacto'] = 'Correo no válido.';
                $envio = numero_o_null($v['costo_envio']);
                $gratis = numero_o_null($v['envio_gratis_desde']);
                if ($envio === null || $envio < 0) $errores['costo_envio'] = 'Escribe un monto (0 si el envío es gratis).';
                if ($v['envio_gratis_desde'] !== '' && ($gratis === null || $gratis < 0)) $errores['envio_gratis_desde'] = 'Escribe un monto o déjalo vacío.';
                foreach (['instagram', 'facebook'] as $r) {
                    if ($v[$r] !== '' && !preg_match('#^https?://#i', $v[$r])) $errores[$r] = 'Pega la dirección completa, empezando con https://';
                }
                if (!in_array(true, $pagos, true)) $errores['pagos'] = 'Deja activa al menos una forma de pago.';

                $nuevoLogo = null;
                if (!$errores && isset($_FILES['logo']) && ($_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                    try {
                        $nuevoLogo = subir_imagen($_FILES['logo'], 'logo');
                    } catch (RuntimeException $ex) {
                        $errores['logo'] = $ex->getMessage();
                    }
                }

                if (!$errores) {
                    $logoActual = aj('logo');
                    $logoFinal = isset($_POST['quitar_logo']) ? '' : $logoActual;
                    if ($nuevoLogo) {
                        $logoFinal = $nuevoLogo;
                    }
                    if ($logoFinal !== $logoActual) {
                        Ajuste::guardar('logo', $logoFinal);
                        borrar_imagen($logoActual); // limpia el logo anterior
                    }
                    $v['color_marca'] = strtolower($v['color_marca']);
                    $v['costo_envio'] = (string) $envio;
                    $v['envio_gratis_desde'] = $v['envio_gratis_desde'] === '' ? '0' : (string) $gratis;
                    foreach (self::CAMPOS as $k) Ajuste::guardar($k, $v[$k]);
                    foreach ($pagos as $k => $on) Ajuste::guardar($k, $on ? '1' : '0');
                    $this->flash('ok', 'Guardamos los ajustes. Los cambios ya se ven en la tienda.');
                    $this->redirigir('admin/ajustes.php');
                }
            }

            if ($accion === 'cuenta') {
                $nombre = $this->texto($_POST, 'nombre');
                $email = strtolower($this->texto($_POST, 'email'));
                $actual = (string) ($_POST['clave_actual'] ?? '');
                $nueva = (string) ($_POST['clave_nueva'] ?? '');

                if (mb_strlen($nombre) < 2) $errores['nombre'] = 'Escribe tu nombre.';
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores['email'] = 'Correo no válido.';
                elseif (Usuario::emailEnUso($email, (int) $admin['id'])) $errores['email'] = 'Ese correo ya está en uso.';
                if (!password_verify($actual, Usuario::hashClave((int) $admin['id']))) $errores['clave_actual'] = 'La contraseña actual no es correcta.';
                if ($nueva !== '' && strlen($nueva) < 8) $errores['clave_nueva'] = 'La nueva contraseña debe tener al menos 8 caracteres.';

                if (!$errores) {
                    Usuario::actualizarCuenta((int) $admin['id'], $nombre, $email, $nueva !== '' ? $nueva : null);
                    if ($nueva !== '') {
                        session_regenerate_id(true);
                    }
                    $this->flash('ok', 'Guardamos los datos de tu cuenta.');
                    $this->redirigir('admin/ajustes.php');
                }
                $cuenta = ['nombre' => $nombre, 'email' => $email];
            }
        }

        $logo = aj('logo');
        $this->panel('ajustes', compact('v', 'pagos', 'errores', 'cuenta', 'logo'), 'Ajustes', 'ajustes', 'Datos de tu tienda');
    }
}
