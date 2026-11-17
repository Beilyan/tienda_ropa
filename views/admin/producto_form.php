<?php /* Formulario de prenda. Recibe: $prod, $id, $v, $stock, $errores, $categorias, $vistaImagen. */
$c = fn(string $k) => CLS_INPUT . (isset($errores[$k]) ? ' !border-rose-400' : '');
$err = fn(string $k) => isset($errores[$k]) ? '<p class="text-sm text-rose-600 mt-1.5" role="alert">' . e($errores[$k]) . '</p>' : '';
?>
<?php if (!empty($errores['general'])): ?><p role="alert" class="mb-6 border border-rose-200 bg-rose-50 text-rose-800 rounded-2xl px-5 py-4"><?= e($errores['general']) ?></p><?php endif; ?>
<?php if ($errores && empty($errores['general'])): ?><p role="alert" class="mb-6 border border-rose-200 bg-rose-50 text-rose-800 rounded-2xl px-5 py-4">Revisa los campos marcados en rojo.</p><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="grid gap-8 lg:grid-cols-[1fr_340px] items-start" novalidate>
    <?= csrf_campo() ?>
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="space-y-8">
        <section class="<?= CLS_CARD ?> p-6 md:p-8">
            <h2 class="text-xl font-semibold mb-6">Datos de la prenda</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2"><label class="<?= CLS_LABEL ?>" for="nombre">Nombre</label>
                    <input class="<?= $c('nombre') ?>" id="nombre" name="nombre" value="<?= e($v['nombre']) ?>" maxlength="160" required><?= $err('nombre') ?></div>
                <div><label class="<?= CLS_LABEL ?>" for="categoria_id">Categoría</label>
                    <select class="<?= $c('categoria_id') ?>" id="categoria_id" name="categoria_id"><option value="">Sin categoría</option>
                        <?php foreach ($categorias as $cat): ?><option value="<?= (int) $cat['id'] ?>" <?= $v['categoria_id'] === (string) $cat['id'] ? 'selected' : '' ?>><?= e($cat['nombre']) ?></option><?php endforeach; ?></select>
                    <?= $err('categoria_id') ?><p class="<?= CLS_AYUDA ?>">¿Falta una? <a class="text-marca font-semibold" href="<?= e(url('admin/categorias.php')) ?>">Créala aquí</a>.</p></div>
                <div><label class="<?= CLS_LABEL ?>" for="color_nombre">Color (nombre)</label>
                    <input class="<?= CLS_INPUT ?>" id="color_nombre" name="color_nombre" value="<?= e($v['color_nombre']) ?>" maxlength="40" placeholder="Ej. Azul marino"></div>
                <div class="sm:col-span-2"><label class="<?= CLS_LABEL ?>" for="descripcion">Descripción</label>
                    <textarea class="<?= CLS_INPUT ?>" id="descripcion" name="descripcion" rows="4" placeholder="Material, corte, cuidados…"><?= e($v['descripcion']) ?></textarea></div>
            </div>
        </section>

        <section class="<?= CLS_CARD ?> p-6 md:p-8" data-precios>
            <h2 class="text-xl font-semibold mb-1">Precio y oferta</h2>
            <p class="text-sm text-slate-500 mb-6">Si pones precio de oferta y fecha de fin, la oferta se apaga sola ese día. No tienes que acordarte.</p>
            <div class="grid gap-5 sm:grid-cols-3">
                <div><label class="<?= CLS_LABEL ?>" for="precio">Precio normal (MXN)</label>
                    <input class="<?= $c('precio') ?>" id="precio" name="precio" value="<?= e($v['precio']) ?>" inputmode="decimal" data-precio required><?= $err('precio') ?></div>
                <div><label class="<?= CLS_LABEL ?>" for="precio_oferta">Precio de oferta</label>
                    <input class="<?= $c('precio_oferta') ?>" id="precio_oferta" name="precio_oferta" value="<?= e($v['precio_oferta']) ?>" inputmode="decimal" placeholder="Sin oferta" data-oferta><?= $err('precio_oferta') ?></div>
                <div><label class="<?= CLS_LABEL ?>" for="oferta_hasta">La oferta termina el</label>
                    <input class="<?= $c('oferta_hasta') ?>" id="oferta_hasta" type="date" name="oferta_hasta" value="<?= e((string) $v['oferta_hasta']) ?>"><?= $err('oferta_hasta') ?></div>
            </div>
            <p data-descuento role="status" aria-live="polite"></p>
        </section>

        <section class="<?= CLS_CARD ?> p-6 md:p-8">
            <h2 class="text-xl font-semibold mb-1">Tallas y stock</h2>
            <p class="text-sm text-slate-500 mb-6">Escribe cuántas piezas hay de cada talla. Deja vacío si no la manejas; pon 0 si se agotó.</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4">
                <?php foreach (TALLAS as $t): ?>
                    <div><label class="<?= CLS_LABEL ?> text-center" for="st-<?= e($t) ?>"><?= e($t) ?></label>
                        <input class="<?= CLS_INPUT ?> text-center" id="st-<?= e($t) ?>" name="stock[<?= e($t) ?>]" value="<?= e($stock[$t] ?? '') ?>" inputmode="numeric" placeholder="—"></div>
                <?php endforeach; ?>
            </div>
            <?= $err('stock') ?>
        </section>
    </div>

    <aside class="space-y-8 lg:sticky lg:top-8">
        <section class="<?= CLS_CARD ?> p-6">
            <h2 class="text-lg font-semibold mb-4">Foto</h2>
            <div id="vista-imagen" class="group aspect-square rounded-2xl overflow-hidden flex items-center justify-center mb-4" style="background-color: <?= e(tinte($v['color_hex'])) ?>">
                <?= imagen_producto($vistaImagen, 'w-[75%] h-[75%]') ?>
            </div>
            <label class="<?= CLS_LABEL ?>" for="imagen"><?= $prod && $prod['imagen'] ? 'Cambiar foto' : 'Subir foto' ?></label>
            <input class="<?= CLS_INPUT ?> text-sm file:mr-3 file:rounded-full file:border-0 file:bg-marca/10 file:text-marca file:font-semibold file:px-4 file:py-2" id="imagen" type="file" name="imagen" accept="image/jpeg,image/png,image/webp" data-vista="vista-imagen">
            <?= $err('imagen') ?>
            <p class="<?= CLS_AYUDA ?>">JPG, PNG o WEBP, de hasta 5 MB. Mejor si es cuadrada. Sin foto se muestra una ilustración.</p>
            <?php if ($prod && $prod['imagen']): ?>
                <label class="flex items-center gap-2 text-sm mt-4"><input type="checkbox" name="quitar_imagen" value="1" class="w-4 h-4 accent-blue-500"> Quitar la foto actual</label>
            <?php endif; ?>
            <div class="mt-5" data-color-par>
                <label class="<?= CLS_LABEL ?>" for="color_hex">Color de la ilustración y del fondo</label>
                <div class="flex gap-3"><input type="color" value="<?= e($v['color_hex']) ?>" class="w-14 h-12 rounded-xl border border-slate-200 p-1 bg-white" aria-label="Elegir color"><input type="text" id="color_hex" name="color_hex" value="<?= e($v['color_hex']) ?>" maxlength="7" class="<?= CLS_INPUT ?>"></div>
            </div>
        </section>

        <section class="<?= CLS_CARD ?> p-6 space-y-4">
            <label class="flex items-start gap-3"><input type="checkbox" name="activo" value="1" class="w-5 h-5 mt-0.5 accent-blue-500" <?= $v['activo'] ? 'checked' : '' ?>><span><strong>Visible en la tienda</strong><span class="block text-sm text-slate-500">Desmárcalo para ocultarla sin borrarla.</span></span></label>
            <label class="flex items-start gap-3"><input type="checkbox" name="destacado" value="1" class="w-5 h-5 mt-0.5 accent-blue-500" <?= $v['destacado'] ? 'checked' : '' ?>><span><strong>Destacada</strong><span class="block text-sm text-slate-500">Aparece primero en el catálogo.</span></span></label>
        </section>

        <button class="<?= CLS_BTN ?> w-full !py-4"><?= $prod ? 'Guardar cambios' : 'Agregar a la tienda' ?></button>
    </aside>
</form>
