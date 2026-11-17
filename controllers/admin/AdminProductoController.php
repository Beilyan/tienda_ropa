<?php
declare(strict_types=1);

/**
 * PRODUCTOS Y PRECIOS
 * Lista con edición rápida por renglón (precio, oferta, fecha de fin, visibilidad) y
 * formulario completo para agregar o editar una prenda con foto, color y stock por talla.
 */
final class AdminProductoController extends AdminController
{
    private const POR_PAGINA = 20;
    private const FILTROS = ['q', 'categoria', 'estado', 'pagina'];

    public function index(): void
    {
        if ($this->esPost()) {
            $this->accionRapida();
        }

        $f = [
            'q' => $this->texto($_GET, 'q'),
            'categoria' => (int) ($_GET['categoria'] ?? 0),
            'estado' => (string) ($_GET['estado'] ?? ''),
        ];
        $q = $f['q'];
        $catId = $f['categoria'];
        $estado = $f['estado'];
        $total = Producto::contarAdmin($f);
        $paginas = max(1, (int) ceil($total / self::POR_PAGINA));
        $pagina = min(max(1, (int) ($_GET['pagina'] ?? 1)), $paginas);
        $productos = Producto::listarAdmin($f, self::POR_PAGINA, ($pagina - 1) * self::POR_PAGINA);
        $categorias = Categoria::todas();
        $qs = http_build_query(array_filter(['q' => $q, 'categoria' => $catId ?: '', 'estado' => $estado, 'pagina' => $pagina > 1 ? $pagina : ''], fn($v) => $v !== ''));

        $this->panel('productos', compact('productos', 'categorias', 'q', 'catId', 'estado', 'total', 'pagina', 'paginas', 'qs'),
            'Productos y precios', 'productos', 'Catálogo', ['href' => 'admin/producto_form.php', 'texto' => '+ Agregar prenda']);
    }

    /** Guardar rápido, quitar oferta o eliminar. Vuelve a la lista con los mismos filtros. */
    private function accionRapida(): never
    {
        $id = (int) ($_POST['id'] ?? 0);
        $p = $id ? Producto::cruda($id) : null;
        if (!$p) {
            $this->flash('error', 'No encontramos esa prenda.');
            $this->volverALista();
        }
        switch ($_POST['accion'] ?? '') {
            case 'guardar_rapido':
                $precio = numero_o_null($_POST['precio'] ?? '');
                $oferta = numero_o_null($_POST['precio_oferta'] ?? '');
                $hasta = fecha_o_null($_POST['oferta_hasta'] ?? '');
                if ($precio === null || $precio <= 0) {
                    $this->flash('error', "«{$p['nombre']}»: escribe un precio válido mayor a cero.");
                } elseif ($oferta !== null && ($oferta <= 0 || $oferta >= $precio)) {
                    $this->flash('error', "«{$p['nombre']}»: el precio de oferta debe ser menor al precio normal.");
                } else {
                    if ($oferta === null) {
                        $hasta = null; // sin oferta no hay fecha de fin
                    }
                    Producto::guardarRapido($id, $precio, $oferta, $hasta, isset($_POST['activo']), isset($_POST['destacado']));
                    $this->flash('ok', "Guardamos «{$p['nombre']}». Ya se ve así en la tienda.");
                }
                break;
            case 'limpiar_oferta':
                Producto::limpiarOferta($id);
                $this->flash('ok', "Quitamos la oferta de «{$p['nombre']}».");
                break;
            case 'eliminar':
                Producto::eliminar($id);
                borrar_imagen($p['imagen']);
                $this->flash('ok', "Eliminamos «{$p['nombre']}». Los pedidos anteriores conservan su historial.");
                break;
        }
        $this->volverALista();
    }

    /** Vuelve a la lista conservando los filtros que había. */
    private function volverALista(): never
    {
        parse_str((string) ($_POST['qs'] ?? ''), $qs);
        $qs = array_intersect_key($qs, array_flip(self::FILTROS));
        $this->redirigir('admin/productos.php' . ($qs ? '?' . http_build_query($qs) : ''));
    }

    public function formulario(): void
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        $prod = $id ? Producto::obtener($id, true) : null;
        if ($id && !$prod) {
            $this->flash('error', 'No encontramos esa prenda.');
            $this->redirigir('admin/productos.php');
        }
        $categorias = Categoria::todas();

        $v = [
            'nombre' => $prod['nombre'] ?? '', 'categoria_id' => (string) ($prod['categoria_id'] ?? ''),
            'descripcion' => $prod['descripcion'] ?? '', 'precio' => isset($prod['precio']) ? (string) (float) $prod['precio'] : '',
            'precio_oferta' => isset($prod['precio_oferta']) ? (string) (float) $prod['precio_oferta'] : '',
            'oferta_hasta' => $prod['oferta_hasta'] ?? '', 'color_nombre' => $prod['color_nombre'] ?? '',
            'color_hex' => $prod['color_hex'] ?? '#258cf0', 'activo' => $prod ? (int) $prod['activo'] : 1, 'destacado' => (int) ($prod['destacado'] ?? 0),
        ];
        $stock = [];
        if ($prod) {
            foreach (Producto::tallas($id) as $t) {
                $stock[$t['talla']] = (string) $t['stock'];
            }
        } else {
            $stock = ['S' => '', 'M' => '', 'L' => '', 'XL' => ''];
        }
        $errores = [];

        if ($this->esPost()) {
            foreach (['nombre', 'categoria_id', 'descripcion', 'precio', 'precio_oferta', 'oferta_hasta', 'color_nombre'] as $k) {
                $v[$k] = $this->texto($_POST, $k);
            }
            $v['color_hex'] = color_valido((string) ($_POST['color_hex'] ?? ''), '#258cf0');
            $v['activo'] = isset($_POST['activo']) ? 1 : 0;
            $v['destacado'] = isset($_POST['destacado']) ? 1 : 0;
            $stock = [];
            foreach (TALLAS as $t) {
                $stock[$t] = trim((string) ($_POST['stock'][$t] ?? ''));
            }

            $precio = numero_o_null($v['precio']);
            $oferta = numero_o_null($v['precio_oferta']);
            $hasta = fecha_o_null($v['oferta_hasta']);
            if (mb_strlen($v['nombre']) < 2) $errores['nombre'] = 'Escribe el nombre de la prenda.';
            if ($precio === null || $precio <= 0) $errores['precio'] = 'Escribe un precio mayor a cero.';
            if ($oferta !== null && ($precio === null || $oferta <= 0 || $oferta >= $precio)) $errores['precio_oferta'] = 'La oferta debe ser menor al precio normal.';
            if ($v['oferta_hasta'] !== '' && $hasta === null) $errores['oferta_hasta'] = 'Fecha no válida.';
            $catId = (int) $v['categoria_id'] ?: null;
            if ($catId !== null && !in_array($catId, array_map('intval', array_column($categorias, 'id')), true)) $errores['categoria_id'] = 'Elige una categoría de la lista.';
            foreach ($stock as $n) {
                if ($n !== '' && (!ctype_digit($n) || (int) $n > 100000)) $errores['stock'] = 'El stock debe ser un número entero (0 o más).';
            }
            $nuevaImagen = null;
            $subio = isset($_FILES['imagen']) && ($_FILES['imagen']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
            if ($subio && !$errores) {
                try {
                    $nuevaImagen = subir_imagen($_FILES['imagen'], 'productos');
                } catch (RuntimeException $ex) {
                    $errores['imagen'] = $ex->getMessage();
                }
            }

            if (!$errores) {
                try {
                    if ($oferta === null) {
                        $hasta = null;
                    }
                    $imagen = $prod['imagen'] ?? null;
                    if (isset($_POST['quitar_imagen'])) {
                        $imagen = null;
                    }
                    if ($nuevaImagen) {
                        $imagen = $nuevaImagen;
                    }
                    Producto::guardar($id, [
                        'categoria_id' => $catId, 'nombre' => $v['nombre'], 'descripcion' => $v['descripcion'] ?: null,
                        'precio' => $precio, 'precio_oferta' => $oferta, 'oferta_hasta' => $hasta,
                        'color_nombre' => $v['color_nombre'] ?: null, 'color_hex' => $v['color_hex'],
                        'imagen' => $imagen, 'activo' => $v['activo'], 'destacado' => $v['destacado'],
                    ], $stock);
                    if (($prod['imagen'] ?? null) && $imagen !== $prod['imagen']) {
                        borrar_imagen($prod['imagen']); // limpia la foto anterior
                    }
                    $this->flash('ok', $prod ? "Guardamos los cambios de «{$v['nombre']}»." : "Agregamos «{$v['nombre']}» a la tienda.");
                    $this->redirigir('admin/productos.php');
                } catch (Throwable $ex) {
                    if ($nuevaImagen) {
                        borrar_imagen($nuevaImagen);
                    }
                    error_log('Guardar producto: ' . $ex->getMessage());
                    $errores['general'] = 'No se pudo guardar. Inténtalo de nuevo.';
                }
            }
        }

        $icono = $v['categoria_id'] !== '' ? Categoria::icono((int) $v['categoria_id']) : null;
        $vistaImagen = ['imagen' => $prod['imagen'] ?? null, 'nombre' => $v['nombre'] ?: 'Vista previa', 'icono' => $icono ?? 'camisa', 'color_hex' => $v['color_hex']];

        $this->panel('producto_form', compact('prod', 'id', 'v', 'stock', 'errores', 'categorias', 'vistaImagen'),
            $prod ? 'Editar prenda' : 'Agregar prenda', 'productos', 'Catálogo',
            ['href' => 'admin/productos.php', 'texto' => '← Volver a la lista', 'estilo' => 'secundario']);
    }
}
