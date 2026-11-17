<?php
/* Layout de la tienda: cabecera + menú + contenido de la página + pie. */
echo View::parcial('layouts/header', ['tituloDoc' => $tituloDoc, 'descripcion' => $descripcion ?? null]);
echo View::parcial('layouts/navbar', compact('u', 'unidades', 'busqueda', 'catsMenu', 'mensajes'));
?>
    <main id="contenido" class="flex-1 px-5 md:px-[30px] py-8 md:py-10">
<?= $contenido ?>
<?= View::parcial('layouts/footer') ?>
