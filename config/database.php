<?php
/* =====================================================
   CONEXIÓN A MYSQL
   Los datos reales los escribe instalar.php en config/local.php
   (archivo privado, excluido de git). También se pueden definir con
   variables de entorno: DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS.
   Devuelve null si la tienda aún no está instalada.
   ===================================================== */

$local = is_file(RAIZ . '/config/local.php') ? require RAIZ . '/config/local.php' : [];
$db = $local['db'] ?? null;

if (getenv('DB_NAME')) {
    $db = [
        'host'    => getenv('DB_HOST') ?: '127.0.0.1',
        'puerto'  => (int) (getenv('DB_PORT') ?: 3306),
        'nombre'  => getenv('DB_NAME'),
        'usuario' => getenv('DB_USER') ?: 'root',
        'clave'   => getenv('DB_PASS') ?: '',
    ];
}

return $db;
