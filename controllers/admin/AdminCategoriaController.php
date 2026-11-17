<?php
declare(strict_types=1);

/** CATEGORÍAS: crear, renombrar, ordenar, ocultar o borrar. */
final class AdminCategoriaController extends AdminController
{
    public function index(): void
    {
        if ($this->esPost()) {
            $accion = (string) ($_POST['accion'] ?? '');
            $id = (int) ($_POST['id'] ?? 0);
            $nombre = $this->texto($_POST, 'nombre');
            $icono = array_key_exists($_POST['icono'] ?? '', ICONOS_CATEGORIA) ? $_POST['icono'] : 'camisa';
            $orden = (int) ($_POST['orden'] ?? 0);

            if ($accion === 'crear' || $accion === 'guardar') {
                if (mb_strlen($nombre) < 2) {
                    $this->flash('error', 'Escribe el nombre de la categoría.');
                } elseif ($accion === 'crear') {
                    Categoria::crear($nombre, $icono, $orden);
                    $this->flash('ok', "Creamos la categoría «$nombre».");
                } elseif (Categoria::existe($id)) {
                    Categoria::actualizar($id, $nombre, $icono, $orden, isset($_POST['activa']));
                    $this->flash('ok', "Guardamos la categoría «$nombre».");
                }
            } elseif ($accion === 'eliminar' && ($anterior = Categoria::nombre($id)) !== null) {
                Categoria::eliminar($id); // sus prendas quedan "Sin categoría"
                $this->flash('ok', "Eliminamos «{$anterior}». Sus prendas quedaron sin categoría.");
            }
            $this->redirigir('admin/categorias.php');
        }

        $this->panel('categorias', ['cats' => Categoria::todasConTotal()], 'Categorías', 'categorias', 'Catálogo');
    }
}
