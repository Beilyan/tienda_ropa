<?php
/* =====================================================
   PUNTO DE ENTRADA ÚNICO
   Todas las páginas pasan por aquí: el .htaccess envía a este archivo
   cualquier dirección que no sea un archivo real (css, js, imágenes).
   ===================================================== */
declare(strict_types=1);

// Servidor de desarrollo de PHP (php -S): que sirva tal cual los archivos reales (css, js, imágenes)
if (PHP_SAPI === 'cli-server') {
    $archivo = __DIR__ . rawurldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
    if (is_file($archivo) && !str_ends_with($archivo, '.php')) {
        return false;
    }
}

define('RAIZ', dirname(__DIR__));
require RAIZ . '/core/arranque.php';

try {
    (require RAIZ . '/routes/web.php')->despachar(RUTA_ACTUAL);
} catch (Throwable $ex) {
    error_log('[tienda] ' . $ex->getMessage() . ' en ' . $ex->getFile() . ':' . $ex->getLine());
    pagina_error('Algo salió mal de nuestro lado. Inténtalo de nuevo en un momento.', 500);
}
