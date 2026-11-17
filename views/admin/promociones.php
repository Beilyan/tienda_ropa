<?php /* Promociones y banners. Recibe: $banners, $b, $vb, $errores, $mostrarForm, $atajos. */
$estadoBanner = function (array $x): array {
    if (!(int) $x['activo']) return ['Oculto', 'gris'];
    if ($x['inicio'] && $x['inicio'] > hoy()) return ['Programado desde ' . fecha_corta($x['inicio']), 'azul'];
    if ($x['fin'] && $x['fin'] < hoy()) return ['Vencido el ' . fecha_corta($x['fin']), 'gris'];
    return ['Visible ahora' . ($x['fin'] ? ' hasta ' . fecha_corta($x['fin']) : ''), 'verde'];
};
$c = fn(string $k) => CLS_INPUT . (isset($errores[$k]) ? ' !border-rose-400' : '');
$err = fn(string $k) => isset($errores[$k]) ? '<p class="text-sm text-rose-600 mt-1.5" role="alert">' . e($errores[$k]) . '</p>' : '';
?>
<?php if ($mostrarForm): ?>
<!-- FORMULARIO DE BANNER -->
<section class="<?= CLS_CARD ?> p-6 md:p-8 mb-10" aria-labelledby="t-banner">
    <h2 id="t-banner" class="text-xl font-semibold mb-6"><?= $b ? 'Editar banner' : 'Nuevo banner' ?></h2>
    <?php if ($errores): ?><p role="alert" class="mb-6 border border-rose-200 bg-rose-50 text-rose-800 rounded-2xl px-5 py-4">Revisa los campos marcados en rojo.</p><?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="grid gap-5 sm:grid-cols-2" novalidate>
        <?= csrf_campo() ?><input type="hidden" name="accion" value="banner"><input type="hidden" name="id" value="<?= (int) ($b['id'] ?? 0) ?>">
        <div><label class="<?= CLS_LABEL ?>" for="etiqueta">Frase pequeña de arriba</label>
            <input class="<?= CLS_INPUT ?>" id="etiqueta" name="etiqueta" value="<?= e($vb['etiqueta']) ?>" maxlength="80" placeholder="Ej. BUEN FIN"></div>
        <div><label class="<?= CLS_LABEL ?>" for="titulo">Título</label>
            <input class="<?= $c('titulo') ?>" id="titulo" name="titulo" value="<?= e($vb['titulo']) ?>" maxlength="120" required placeholder="Ej. Hasta 40% de descuento"><?= $err('titulo') ?></div>
        <div><label class="<?= CLS_LABEL ?>" for="titulo_cursiva">Segunda línea (en cursiva)</label>
            <input class="<?= CLS_INPUT ?>" id="titulo_cursiva" name="titulo_cursiva" value="<?= e($vb['titulo_cursiva']) ?>" maxlength="120" placeholder="Ej. en toda la tienda."></div>
        <div><label class="<?= CLS_LABEL ?>" for="texto">Texto corto</label>
            <input class="<?= CLS_INPUT ?>" id="texto" name="texto" value="<?= e($vb['texto']) ?>" maxlength="255"></div>
        <div><label class="<?= CLS_LABEL ?>" for="boton_texto">Texto del botón</label>
            <input class="<?= CLS_INPUT ?>" id="boton_texto" name="boton_texto" value="<?= e($vb['boton_texto']) ?>" maxlength="40"></div>
        <div><label class="<?= CLS_LABEL ?>" for="boton_enlace">A dónde lleva el botón</label>
            <select class="<?= CLS_INPUT ?> mb-2" aria-label="Atajos de enlace" onchange="if(this.value)document.getElementById('boton_enlace').value=this.value">
                <option value="">Elegir un atajo…</option>
                <option value="lista_ropa.php">Todo el catálogo</option>
                <option value="lista_ropa.php?oferta=1">Solo ofertas</option>
                <?php foreach ($atajos as $cat): ?>
                    <option value="lista_ropa.php?categoria=<?= e($cat['slug']) ?>">Categoría: <?= e($cat['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
            <input class="<?= $c('boton_enlace') ?>" id="boton_enlace" name="boton_enlace" value="<?= e($vb['boton_enlace']) ?>" maxlength="255"><?= $err('boton_enlace') ?></div>
        <div data-color-par><label class="<?= CLS_LABEL ?>" for="color_fondo">Color de fondo</label>
            <div class="flex gap-3"><input type="color" value="<?= e($vb['color_fondo']) ?>" class="w-14 h-12 rounded-xl border border-slate-200 p-1 bg-white" aria-label="Elegir color"><input type="text" id="color_fondo" name="color_fondo" value="<?= e($vb['color_fondo']) ?>" maxlength="7" class="<?= CLS_INPUT ?>"></div></div>
        <div><label class="<?= CLS_LABEL ?>" for="inicio">Mostrar desde (opcional)</label>
            <input class="<?= $c('inicio') ?>" id="inicio" type="date" name="inicio" value="<?= e((string) $vb['inicio']) ?>"><?= $err('inicio') ?>
            <p class="<?= CLS_AYUDA ?>">Déjalo vacío para mostrarlo desde hoy.</p></div>
        <div><label class="<?= CLS_LABEL ?>" for="fin">Mostrar hasta (opcional)</label>
            <input class="<?= $c('fin') ?>" id="fin" type="date" name="fin" value="<?= e((string) $vb['fin']) ?>"><?= $err('fin') ?>
            <p class="<?= CLS_AYUDA ?>">Después de esta fecha el banner se oculta solo.</p></div>
        <div><label class="<?= CLS_LABEL ?>" for="orden">Posición (1 se muestra primero)</label>
            <input class="<?= CLS_INPUT ?>" id="orden" type="number" min="0" name="orden" value="<?= (int) $vb['orden'] ?>"></div>
        <div class="sm:col-span-2">
            <label class="<?= CLS_LABEL ?>" for="imagen">Imagen a la derecha (opcional)</label>
            <div class="flex flex-wrap items-center gap-4">
                <div id="vista-banner" class="w-28 h-28 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center text-xs text-slate-400 text-center p-2"><?php if (!empty($b['imagen'])): ?><img src="<?= e(url_imagen($b['imagen'])) ?>" alt="Imagen actual del banner" class="w-full h-full object-cover"><?php else: ?>Sin imagen<?php endif; ?></div>
                <div class="flex-1 min-w-60">
                    <input class="<?= CLS_INPUT ?> text-sm file:mr-3 file:rounded-full file:border-0 file:bg-marca/10 file:text-marca file:font-semibold file:px-4 file:py-2" id="imagen" type="file" name="imagen" accept="image/jpeg,image/png,image/webp" data-vista="vista-banner"><?= $err('imagen') ?>
                    <p class="<?= CLS_AYUDA ?>">Mejor con fondo transparente (PNG). Sin imagen se muestra la ilustración de la percha.</p>
                    <?php if (!empty($b['imagen'])): ?><label class="flex items-center gap-2 text-sm mt-2"><input type="checkbox" name="quitar_imagen" value="1" class="w-4 h-4 accent-blue-500"> Quitar la imagen actual</label><?php endif; ?>
                </div>
            </div>
        </div>
        <label class="sm:col-span-2 flex items-center gap-3"><input type="checkbox" name="activo" value="1" class="w-5 h-5 accent-blue-500" <?= $vb['activo'] ? 'checked' : '' ?>> <span><strong>Activo</strong> <span class="text-slate-500 text-sm">(se muestra dentro de sus fechas)</span></span></label>
        <div class="sm:col-span-2 flex flex-wrap gap-3">
            <button class="<?= CLS_BTN ?>"><?= $b ? 'Guardar banner' : 'Crear banner' ?></button>
            <a href="<?= e(url('admin/promociones.php')) ?>" class="<?= CLS_BTN_2 ?>">Cancelar</a>
        </div>
    </form>
</section>
<?php endif; ?>

<!-- BARRA DE AVISO -->
<section class="<?= CLS_CARD ?> p-6 md:p-8 mb-10" aria-labelledby="t-aviso">
    <h2 id="t-aviso" class="text-xl font-semibold mb-1">Barra de aviso</h2>
    <p class="text-sm text-slate-500 mb-6">La franja oscura que aparece arriba en todas las páginas. Ideal para envíos gratis, descuentos o avisos de horario.</p>
    <form method="post" class="grid gap-5 sm:grid-cols-2 items-end">
        <?= csrf_campo() ?><input type="hidden" name="accion" value="aviso">
        <div class="sm:col-span-2"><label class="<?= CLS_LABEL ?>" for="aviso_texto">Mensaje</label>
            <input class="<?= CLS_INPUT ?>" id="aviso_texto" name="aviso_texto" value="<?= e(aj('aviso_texto')) ?>" maxlength="140" placeholder="Ej. 2x1 en sudaderas este fin de semana"></div>
        <div><label class="<?= CLS_LABEL ?>" for="aviso_enlace">Enlace al hacer clic (opcional)</label>
            <input class="<?= CLS_INPUT ?>" id="aviso_enlace" name="aviso_enlace" value="<?= e(aj('aviso_enlace')) ?>" placeholder="Ej. lista_ropa.php?oferta=1"></div>
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <label class="flex items-center gap-3"><input type="checkbox" name="aviso_activo" value="1" class="w-5 h-5 accent-blue-500" <?= aj('aviso_activo') === '1' ? 'checked' : '' ?>> Mostrar la barra</label>
            <button class="<?= CLS_BTN ?>">Guardar aviso</button>
        </div>
    </form>
</section>

<!-- LISTA DE BANNERS -->
<section aria-labelledby="t-lista">
    <h2 id="t-lista" class="text-xl font-semibold mb-4">Banners del inicio</h2>
    <?php if ($banners): ?>
        <ul class="space-y-3">
            <?php foreach ($banners as $x): [$txtEstado, $tono] = $estadoBanner($x); $bid = (int) $x['id']; ?>
                <li class="<?= CLS_CARD ?> p-4 flex flex-wrap items-center gap-4">
                    <span class="w-24 h-16 rounded-xl shrink-0" style="background-color: <?= e(color_valido($x['color_fondo'])) ?>" aria-hidden="true"></span>
                    <div class="flex-1 min-w-52">
                        <p class="font-semibold"><?= e($x['titulo']) ?> <span class="italic font-serif text-slate-500"><?= e((string) $x['titulo_cursiva']) ?></span></p>
                        <p class="text-sm text-slate-500 mt-1 flex flex-wrap gap-2 items-center"><?= chip($txtEstado, $tono) ?> <span>Posición <?= (int) $x['orden'] ?></span></p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="<?= e(url('admin/promociones.php?editar=' . $bid)) ?>" class="<?= CLS_BTN_2 ?> !py-2 !px-5 text-sm">Editar</a>
                        <form method="post"><?= csrf_campo() ?><input type="hidden" name="accion" value="alternar_banner"><input type="hidden" name="id" value="<?= $bid ?>">
                            <button class="text-sm text-slate-500 hover:text-marca underline underline-offset-4"><?= (int) $x['activo'] ? 'Ocultar' : 'Mostrar' ?></button></form>
                        <form method="post" data-confirmar="¿Eliminar este banner?"><?= csrf_campo() ?><input type="hidden" name="accion" value="eliminar_banner"><input type="hidden" name="id" value="<?= $bid ?>">
                            <button class="text-sm text-slate-400 hover:text-rose-600 underline underline-offset-4">Eliminar</button></form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="<?= CLS_CARD ?> p-8 text-center text-slate-500">No hay banners. Crea uno para anunciar tus ofertas en la portada.</p>
    <?php endif; ?>
</section>
