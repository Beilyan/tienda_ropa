<?php
declare(strict_types=1);

/**
 * PREPARACIÓN AUTOMÁTICA (primera vez que se abre la tienda)
 * No hay pantalla de instalación: si todavía no existe config/local.php, la tienda se conecta sola a
 * MySQL con los datos por defecto de AMPPS (config/app.php > base_de_datos), crea la base y las
 * tablas, la cuenta única del dueño (config/app.php > dueno_inicial) y, si se pide, prendas de ejemplo.
 * Para empezar de cero: borra la base de datos y el archivo config/local.php.
 */
final class Preparacion extends Modelo
{
    /** Prueba los datos de conexión por defecto y devuelve la conexión que funcione, o null. */
    private static function conectar(): ?array
    {
        $c = Config::get('app.base_de_datos');
        foreach ($c['puertos'] as $puerto) {
            foreach ($c['accesos'] as [$usuario, $clave]) {
                try {
                    $pdo = new PDO("mysql:host={$c['host']};port=$puerto;charset=utf8mb4", $usuario, $clave, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                    return ['pdo' => $pdo, 'db' => ['host' => $c['host'], 'puerto' => (int) $puerto, 'nombre' => $c['nombre'], 'usuario' => $usuario, 'clave' => $clave]];
                } catch (PDOException) {
                    // se prueba la siguiente combinación
                }
            }
        }
        return null;
    }

    /**
     * Crea todo lo necesario y guarda config/local.php.
     * @throws RuntimeException con un mensaje claro si algo no se puede hacer
     */
    public static function ejecutar(): void
    {
        $conexion = self::conectar();
        if ($conexion === null) {
            throw new RuntimeException('No pudimos conectar con la base de datos. Abre AMPPS, enciende MySQL y recarga esta página.');
        }
        $pdo = $conexion['pdo'];
        $nombreBd = $conexion['db']['nombre'];
        $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $nombreBd . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $pdo->exec('USE `' . $nombreBd . '`');
        self::ejecutarSql($pdo, RAIZ . '/database/schema.sql');

        $d = Config::get('app.dueno_inicial');
        $pdo->prepare('INSERT INTO usuarios (nombre, email, password_hash, rol) SELECT ?, ?, ?, ? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE rol = \'admin\')')
            ->execute([$d['nombre'], mb_strtolower($d['email']), password_hash($d['clave'], PASSWORD_DEFAULT), 'admin']);
        if (Config::get('app.datos_demo')) {
            self::datosDemo($pdo);
        }

        $archivo = RAIZ . '/config/local.php';
        $config = ['db' => $conexion['db'], 'zona_horaria' => Config::get('app.zona_horaria')];
        if (file_put_contents($archivo, "<?php\n// Generado automáticamente la primera vez. Datos privados: no los subas a un repositorio.\nreturn " . var_export($config, true) . ";\n") === false) {
            throw new RuntimeException('No se pudo guardar config/local.php. Revisa los permisos de la carpeta config.');
        }
        foreach (['public/uploads/productos', 'public/uploads/banners', 'public/uploads/logo'] as $dir) {
            if (!is_dir(RAIZ . '/' . $dir)) {
                @mkdir(RAIZ . '/' . $dir, 0775, true);
            }
        }
    }

    /** Ejecuta cada sentencia de un archivo .sql por separado. */
    private static function ejecutarSql(PDO $pdo, string $archivo): void
    {
        $sql = (string) file_get_contents($archivo);
        $sql = (string) preg_replace('/^\s*--.*$/m', '', $sql);
        foreach (preg_split('/;\s*[\r\n]+/', $sql) as $sentencia) {
            if (trim($sentencia) !== '') {
                $pdo->exec($sentencia);
            }
        }
    }

    private static function datosDemo(PDO $pdo): void
    {
        $cats = [
            ['Camisas', 'camisas', 'camisa', 1], ['Chaquetas', 'chaquetas', 'chaqueta', 2],
            ['Vestidos', 'vestidos', 'vestido', 3], ['Sudaderas', 'sudaderas', 'sudadera', 4],
            ['Pantalones', 'pantalones', 'pantalon', 5],
        ];
        $st = $pdo->prepare('INSERT IGNORE INTO categorias (nombre, slug, icono, orden) VALUES (?,?,?,?)');
        foreach ($cats as $c) {
            $st->execute($c);
        }
        $id = fn(string $slug) => (int) $pdo->query("SELECT id FROM categorias WHERE slug = " . $pdo->quote($slug))->fetchColumn();
        $fin = date('Y-m-d', strtotime('+20 days'));
        // [nombre, categoría, descripción, precio, oferta, color, hex, destacado, tallas]
        $productos = [
            ['Camisa Essential', 'camisas', 'Camisa de algodón suave con corte recto. Una base cómoda para todos los días.', 890, null, 'Azul', '#258cf0', 1, ['S' => 8, 'M' => 12, 'L' => 10, 'XL' => 4]],
            ['Chaqueta Urban', 'chaquetas', 'Chaqueta ligera con cierre frontal y bolsillos. Ideal para entretiempo.', 1490, 1190, 'Carbón', '#27323b', 1, ['M' => 6, 'L' => 5, 'XL' => 2]],
            ['Vestido Aura', 'vestidos', 'Vestido fluido de caída suave, cómodo y fácil de combinar.', 1190, null, 'Arena', '#cba27f', 1, ['S' => 5, 'M' => 7, 'L' => 3]],
            ['Sudadera Classic', 'sudaderas', 'Sudadera de felpa ligera con bolsillo canguro.', 990, null, 'Gris', '#9eacb8', 0, ['S' => 9, 'M' => 14, 'L' => 11, 'XL' => 6]],
            ['Camisa Lino Arena', 'camisas', 'Camisa de lino fresca, perfecta para días de calor.', 950, null, 'Arena clara', '#d8c3a5', 0, ['S' => 4, 'M' => 6, 'L' => 6]],
            ['Pantalón Recto Denim', 'pantalones', 'Pantalón de mezclilla de tiro medio y pierna recta.', 1090, null, 'Índigo', '#3b4a6b', 0, ['S' => 5, 'M' => 8, 'L' => 8, 'XL' => 3]],
            ['Vestido Floral Menta', 'vestidos', 'Vestido ligero con corte en A y tela que no se arruga.', 1290, 990, 'Menta', '#8fd9bc', 0, ['S' => 3, 'M' => 3, 'L' => 2]],
            ['Sudadera Oversize Negra', 'sudaderas', 'Sudadera holgada de algodón grueso, calentita y resistente.', 1050, null, 'Negro', '#1e1e1e', 0, ['M' => 2, 'L' => 6, 'XL' => 5]],
        ];
        $ins = $pdo->prepare('INSERT INTO productos (categoria_id, nombre, slug, descripcion, precio, precio_oferta, oferta_hasta, color_nombre, color_hex, destacado)
                              VALUES (?,?,?,?,?,?,?,?,?,?)');
        $talla = $pdo->prepare('INSERT INTO producto_tallas (producto_id, talla, stock) VALUES (?,?,?)');
        foreach ($productos as [$nombre, $cat, $desc, $precio, $oferta, $color, $hex, $dest, $tallas]) {
            $slug = slugify($nombre);
            if ((int) $pdo->query('SELECT COUNT(*) FROM productos WHERE slug = ' . $pdo->quote($slug))->fetchColumn() > 0) {
                continue;
            }
            $ins->execute([$id($cat), $nombre, $slug, $desc, $precio, $oferta, $oferta ? $fin : null, $color, $hex, $dest]);
            $pid = (int) $pdo->lastInsertId();
            foreach ($tallas as $t => $stock) {
                $talla->execute([$pid, $t, $stock]);
            }
        }
        $pdo->prepare("INSERT INTO banners (etiqueta, titulo, titulo_cursiva, texto, boton_texto, boton_enlace, color_fondo, orden)
                       VALUES ('NUEVA COLECCIÓN', 'Tu estilo,', 'sin reglas.', 'Prendas pensadas para moverte con comodidad.', 'Ver colección', 'lista_ropa.php', '#258cf0', 1),
                              ('OFERTAS DE TEMPORADA', 'Precios que', 'te van a gustar.', 'Aprovecha las ofertas antes de que terminen.', 'Ver ofertas', 'lista_ropa.php?oferta=1', '#202c38', 2)")->execute();
        $pdo->prepare("INSERT IGNORE INTO cupones (codigo, tipo, valor, minimo_compra) VALUES ('BIENVENIDA10', 'porcentaje', 10, 500)")->execute();
    }
}
