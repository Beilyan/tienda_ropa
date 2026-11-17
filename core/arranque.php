<?php
/* =====================================================
   ARRANQUE DE LA APLICACIÓN
   Lo carga public/index.php. Prepara: versión de PHP, carga automática
   de clases, helpers, configuración, ruta base, sesión y ruta pedida.
   ===================================================== */
declare(strict_types=1);

if (PHP_VERSION_ID < 80000) {
    http_response_code(500);
    exit('Esta tienda necesita PHP 8.0 o superior. Cambia la versión de PHP en el panel de AMPPS.');
}

mb_internal_encoding('UTF-8');

/* ---------- 1. Carga automática de clases ---------- */
spl_autoload_register(function (string $clase): void {
    foreach (['core', 'models', 'controllers', 'controllers/admin'] as $dir) {
        $archivo = RAIZ . '/' . $dir . '/' . $clase . '.php';
        if (is_file($archivo)) {
            require $archivo;
            return;
        }
    }
});

/* ---------- 2. Helpers y configuración ---------- */
require RAIZ . '/helpers/functions.php';
require RAIZ . '/helpers/ui.php';
require RAIZ . '/helpers/imagenes.php';
Config::cargar('app'); // define TALLAS, ESTADOS_PEDIDO, METODOS_PAGO, ICONOS_CATEGORIA

/* ---------- 3. Ruta base: http://localhost/tienda_ropa o un dominio ---------- */
/**
 * La carpeta pública (public/) puede ser la raíz del sitio o quedar dentro
 * de /tienda_ropa (AMPPS): el .htaccess de la raíz la reescribe. En ambos
 * casos las URL de la tienda no incluyen "/public".
 */
function calcular_base(): string
{
    $publica = str_replace('\\', '/', (string) realpath(RAIZ . '/public'));
    $docRoot = str_replace('\\', '/', (string) realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    if ($docRoot !== '' && stripos($publica, $docRoot) === 0) {
        $rel = rtrim(substr($publica, strlen($docRoot)), '/');   // '' | '/public' | '/tienda_ropa/public'
        return str_ends_with($rel, '/public') ? substr($rel, 0, -7) : $rel;
    }
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $dir = rtrim(dirname($script), '/');
    return str_ends_with($dir, '/public') ? substr($dir, 0, -7) : $dir;
}
define('BASE', calcular_base());

/** Página pedida, sin la ruta base: "lista_ropa.php", "admin/productos.php"… */
function calcular_ruta(): string
{
    $ruta = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
    if (BASE !== '' && stripos($ruta, BASE) === 0) {
        $ruta = substr($ruta, strlen(BASE));
    }
    $ruta = trim($ruta, '/');
    if (str_starts_with($ruta, 'public/')) {
        $ruta = substr($ruta, 7);
    }
    return match ($ruta) {
        '', 'public' => 'principal.php',
        'admin'      => 'admin/index.php',
        default      => $ruta,
    };
}
define('RUTA_ACTUAL', calcular_ruta());

/* ---------- 4. Instalación, zona horaria y sesión ---------- */
if (!Config::instalada() && RUTA_ACTUAL !== 'instalar.php' && !preg_match('#^assets/#', RUTA_ACTUAL)) {
    redirigir('instalar.php');
}
date_default_timezone_set(Config::zonaHoraria());
Sesion::iniciar();
