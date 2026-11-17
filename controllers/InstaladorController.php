<?php
declare(strict_types=1);

/**
 * INSTALADOR (se usa una sola vez)
 * Guarda la configuración en config/local.php, crea la cuenta del dueño y, si se desea,
 * carga datos de ejemplo. Abre: http://localhost/tienda_ropa/instalar.php
 */
final class InstaladorController extends Controller
{
    public function instalar(): void
    {
        $error = '';
        $exito = false;
        $instalada = Instalador::yaInstalada();
        $v = [
            'nombre_tienda' => 'MODA', 'admin_nombre' => '', 'admin_email' => '', 'demo' => '1',
        ];

        if (!$instalada && $this->esPost()) {
            foreach ($v as $k => $_) {
                $v[$k] = $this->texto($_POST, $k);
            }
            $v['demo'] = isset($_POST['demo']) ? '1' : '';
            $adminClave = (string) ($_POST['admin_clave'] ?? '');
            try {
                if ($v['nombre_tienda'] === '' || $v['admin_nombre'] === '') {
                    throw new RuntimeException('Escribe el nombre de la tienda y tu nombre.');
                }
                if (!filter_var($v['admin_email'], FILTER_VALIDATE_EMAIL)) {
                    throw new RuntimeException('Escribe un correo válido para entrar al panel.');
                }
                if (mb_strlen($adminClave) < 8) {
                    throw new RuntimeException('La contraseña del panel debe tener al menos 8 caracteres.');
                }

                $db = Instalador::instalar($v, $adminClave);
                $this->guardarConfiguracion($db);
                foreach (['public/uploads/productos', 'public/uploads/banners'] as $d) {
                    if (!is_dir(RAIZ . '/' . $d)) {
                        @mkdir(RAIZ . '/' . $d, 0775, true);
                    }
                }
                $exito = true;
            } catch (Throwable $ex) {
                if (!$ex instanceof RuntimeException) {
                    error_log('Instalador: ' . $ex->getMessage());
                }
                $error = $ex instanceof RuntimeException ? $ex->getMessage() : 'No se pudo terminar la instalación. Vuelve a intentarlo; si sigue igual, avisa a quien te instaló la tienda.';
            }
        }

        $this->vista('instalador', compact('instalada', 'exito', 'error', 'v'), null);
    }

    private function guardarConfiguracion(array $db): void
    {
        $config = [
            'db' => $db,
            'zona_horaria' => Config::get('app.zona_horaria'),
        ];
        $archivo = RAIZ . '/config/local.php';
        if (file_put_contents($archivo, "<?php\n// Generado por instalar.php. Datos privados: no los subas a un repositorio.\nreturn " . var_export($config, true) . ";\n") === false) {
            throw new RuntimeException('No se pudo guardar config/local.php. Revisa los permisos de la carpeta config.');
        }
    }
}
