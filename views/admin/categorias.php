<?php /* Categorías. Recibe: $cats. */ ?>
<section class="<?= CLS_CARD ?> p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Nueva categoría</h2>
    <form method="post" class="grid gap-4 sm:grid-cols-[1fr_200px_110px_auto] items-end">
        <?= csrf_campo() ?><input type="hidden" name="accion" value="crear">
        <div><label class="<?= CLS_LABEL ?>" for="n-nombre">Nombre</label><input class="<?= CLS_INPUT ?>" id="n-nombre" name="nombre" maxlength="80" required placeholder="Ej. Playeras"></div>
        <div><label class="<?= CLS_LABEL ?>" for="n-icono">Dibujo de la prenda</label>
            <select class="<?= CLS_INPUT ?>" id="n-icono" name="icono"><?php foreach (ICONOS_CATEGORIA as $k => $t): ?><option value="<?= $k ?>"><?= e($t) ?></option><?php endforeach; ?></select></div>
        <div><label class="<?= CLS_LABEL ?>" for="n-orden">Orden</label><input class="<?= CLS_INPUT ?>" id="n-orden" name="orden" type="number" value="<?= count($cats) + 1 ?>" min="0"></div>
        <button class="<?= CLS_BTN ?>">Crear</button>
    </form>
</section>

<?php if ($cats): ?>
<ul class="space-y-3">
    <?php foreach ($cats as $c): $id = (int) $c['id']; ?>
        <li>
            <form method="post" class="<?= CLS_CARD ?> p-4 grid gap-4 sm:grid-cols-[1fr_200px_110px_auto] items-center">
                <?= csrf_campo() ?><input type="hidden" name="accion" value="guardar"><input type="hidden" name="id" value="<?= $id ?>">
                <div><label class="sr-only" for="c-n<?= $id ?>">Nombre</label><input class="<?= CLS_INPUT ?>" id="c-n<?= $id ?>" name="nombre" value="<?= e($c['nombre']) ?>" maxlength="80" required>
                    <p class="<?= CLS_AYUDA ?>"><?= (int) $c['total'] ?> <?= (int) $c['total'] === 1 ? 'prenda' : 'prendas' ?> <?= !(int) $c['activa'] ? '· ' . chip('Oculta', 'gris') : '' ?></p></div>
                <div><label class="sr-only" for="c-i<?= $id ?>">Dibujo</label>
                    <select class="<?= CLS_INPUT ?>" id="c-i<?= $id ?>" name="icono"><?php foreach (ICONOS_CATEGORIA as $k => $t): ?><option value="<?= $k ?>" <?= $c['icono'] === $k ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></select></div>
                <div><label class="sr-only" for="c-o<?= $id ?>">Orden</label><input class="<?= CLS_INPUT ?>" id="c-o<?= $id ?>" name="orden" type="number" value="<?= (int) $c['orden'] ?>" min="0"></div>
                <div class="flex items-center gap-4 flex-wrap">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="activa" value="1" class="w-4 h-4 accent-blue-500" <?= (int) $c['activa'] ? 'checked' : '' ?>> Visible</label>
                    <button class="<?= CLS_BTN ?> !px-5 !py-2.5 text-sm">Guardar</button>
                    <button type="submit" form="del-c<?= $id ?>" class="text-sm text-slate-400 hover:text-rose-600 underline underline-offset-4">Eliminar</button>
                </div>
            </form>
            <form method="post" id="del-c<?= $id ?>" data-confirmar="¿Eliminar la categoría «<?= e($c['nombre']) ?>»? Sus prendas no se borran, quedarán sin categoría.">
                <?= csrf_campo() ?><input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= $id ?>">
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <p class="<?= CLS_CARD ?> p-8 text-center text-slate-500">Aún no hay categorías. Crea la primera arriba.</p>
<?php endif; ?>
