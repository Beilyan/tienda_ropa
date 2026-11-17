<?php
declare(strict_types=1);

/**
 * PROMOCIONES: barra de aviso y banners del inicio.
 * El dueño crea y programa (con fecha de inicio y fin) lo que se ve en la portada.
 */
final class AdminPromocionController extends AdminController
{
    public function index(): void
    {
        $errores = [];
        $editarId = (int) ($_GET['editar'] ?? $_POST['id'] ?? 0);
        $nuevo = isset($_GET['nuevo']);
        $b = $editarId ? Banner::porId($editarId) : null;
        $vb = [
            'etiqueta' => $b['etiqueta'] ?? '', 'titulo' => $b['titulo'] ?? '', 'titulo_cursiva' => $b['titulo_cursiva'] ?? '',
            'texto' => $b['texto'] ?? '', 'boton_texto' => $b['boton_texto'] ?? 'Ver colección', 'boton_enlace' => $b['boton_enlace'] ?? 'lista_ropa.php',
            'color_fondo' => $b['color_fondo'] ?? color_valido(aj('color_marca')), 'activo' => $b ? (int) $b['activo'] : 1,
            'orden' => $b['orden'] ?? 1, 'inicio' => $b['inicio'] ?? '', 'fin' => $b['fin'] ?? '',
        ];

        if ($this->esPost()) {
            $accion = (string) ($_POST['accion'] ?? '');

            if ($accion === 'aviso') {
                Ajuste::guardar('aviso_activo', isset($_POST['aviso_activo']) ? '1' : '0');
                Ajuste::guardar('aviso_texto', mb_substr($this->texto($_POST, 'aviso_texto'), 0, 140));
                $enlace = $this->texto($_POST, 'aviso_enlace');
                Ajuste::guardar('aviso_enlace', enlace_valido($enlace) ? $enlace : '');
                $this->flash('ok', 'Guardamos la barra de aviso. Ya se ve en toda la tienda.');
                $this->redirigir('admin/promociones.php');
            }
            if ($accion === 'eliminar_banner' && ($x = Banner::porId((int) ($_POST['id'] ?? 0)))) {
                Banner::eliminar((int) $x['id']);
                borrar_imagen($x['imagen']);
                $this->flash('ok', 'Eliminamos el banner.');
                $this->redirigir('admin/promociones.php');
            }
            if ($accion === 'alternar_banner' && ($x = Banner::porId((int) ($_POST['id'] ?? 0)))) {
                Banner::alternar((int) $x['id']);
                $this->flash('ok', $x['activo'] ? 'Ocultamos el banner.' : 'El banner vuelve a mostrarse (si está dentro de sus fechas).');
                $this->redirigir('admin/promociones.php');
            }
            if ($accion === 'banner') {
                foreach (['etiqueta', 'titulo', 'titulo_cursiva', 'texto', 'boton_texto', 'boton_enlace'] as $k) {
                    $vb[$k] = $this->texto($_POST, $k);
                }
                $vb['color_fondo'] = color_valido((string) ($_POST['color_fondo'] ?? ''));
                $vb['activo'] = isset($_POST['activo']) ? 1 : 0;
                $vb['orden'] = (int) ($_POST['orden'] ?? 1);
                $vb['inicio'] = $this->texto($_POST, 'inicio');
                $vb['fin'] = $this->texto($_POST, 'fin');
                $ini = fecha_o_null($vb['inicio']);
                $fin = fecha_o_null($vb['fin']);

                if (mb_strlen($vb['titulo']) < 2) $errores['titulo'] = 'Escribe el título del banner.';
                if ($vb['inicio'] !== '' && !$ini) $errores['inicio'] = 'Fecha no válida.';
                if ($vb['fin'] !== '' && !$fin) $errores['fin'] = 'Fecha no válida.';
                if ($ini && $fin && $fin < $ini) $errores['fin'] = 'La fecha de fin debe ser posterior a la de inicio.';
                if ($vb['boton_enlace'] !== '' && !enlace_valido($vb['boton_enlace'])) {
                    $errores['boton_enlace'] = 'Usa una página de la tienda (ej. lista_ropa.php?oferta=1) o una dirección que empiece con https://';
                }
                $nueva = null;
                if (!$errores && isset($_FILES['imagen']) && ($_FILES['imagen']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                    try {
                        $nueva = subir_imagen($_FILES['imagen'], 'banners');
                    } catch (RuntimeException $ex) {
                        $errores['imagen'] = $ex->getMessage();
                    }
                }
                if (!$errores) {
                    $imagen = $b['imagen'] ?? null;
                    if (isset($_POST['quitar_imagen'])) $imagen = null;
                    if ($nueva) $imagen = $nueva;
                    $datos = [
                        'etiqueta' => $vb['etiqueta'] ?: null, 'titulo' => $vb['titulo'], 'titulo_cursiva' => $vb['titulo_cursiva'] ?: null,
                        'texto' => $vb['texto'] ?: null, 'boton_texto' => $vb['boton_texto'] ?: null, 'boton_enlace' => $vb['boton_enlace'] ?: null,
                        'color_fondo' => $vb['color_fondo'], 'imagen' => $imagen, 'activo' => $vb['activo'], 'orden' => $vb['orden'],
                        'inicio' => $ini, 'fin' => $fin,
                    ];
                    if ($b) {
                        Banner::actualizar((int) $b['id'], $datos);
                        if (($b['imagen'] ?? null) && $imagen !== $b['imagen']) borrar_imagen($b['imagen']);
                        $this->flash('ok', 'Guardamos el banner.');
                    } else {
                        Banner::crear($datos);
                        $this->flash('ok', 'Creamos el banner. Ya aparece en el inicio dentro de sus fechas.');
                    }
                    $this->redirigir('admin/promociones.php');
                }
                $nuevo = !$b;
            }
        }

        $banners = Banner::todos();
        $mostrarForm = $nuevo || $b;
        $atajos = Categoria::atajos();
        $this->panel('promociones', compact('banners', 'b', 'vb', 'errores', 'mostrarForm', 'atajos'),
            'Promociones y banners', 'promociones', 'Publicidad',
            $mostrarForm ? null : ['href' => 'admin/promociones.php?nuevo=1', 'texto' => '+ Nuevo banner']);
    }
}
