<?php /* Productos y precios. Recibe: $productos, $categorias, $q, $catId, $estado, $total, $pagina, $paginas, $qs. */
$mini = 'w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 outline-none focus:border-marca focus:ring-2 focus:ring-marca/20';
?>
<!-- FILTROS -->
<form method="get" class="<?= CLS_CARD ?> p-4 mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_200px_200px_auto] items-end">
    <div><label class="<?= CLS_LABEL ?>" for="q">Buscar</label><input class="<?= CLS_INPUT ?>" id="q" type="search" name="q" value="<?= e($q) ?>" placeholder="Nombre o categoría"></div>
    <div><label class="<?= CLS_LABEL ?>" for="categoria">Categoría</label>
        <select class="<?= CLS_INPUT ?>" id="categoria" name="categoria"><option value="">Todas</option>
            <?php foreach ($categorias as $c): ?><option value="<?= (int) $c['id'] ?>" <?= $catId === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['nombre']) ?></option><?php endforeach; ?></select></div>
    <div><label class="<?= CLS_LABEL ?>" for="estado">Mostrar</label>
        <select class="<?= CLS_INPUT ?>" id="estado" name="estado">
            <?php foreach (['' => 'Todas', 'activos' => 'Visibles en la tienda', 'ocultos' => 'Ocultas', 'agotados' => 'Agotadas', 'ofertas' => 'Con oferta vigente', 'vencidas' => 'Con oferta vencida'] as $k => $t): ?>
                <option value="<?= $k ?>" <?= $estado === (string) $k ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?></select></div>
    <button class="<?= CLS_BTN ?>">Filtrar</button>
</form>

<p class="text-sm text-slate-500 mb-4" role="status"><?= $total ?> <?= $total === 1 ? 'prenda' : 'prendas' ?>. Cambia un valor y pulsa <strong>Guardar</strong> en ese renglón.</p>

<?php if (!$productos): ?>
    <div class="<?= CLS_CARD ?> p-10 text-center">
        <p class="text-slate-500 mb-5">No hay prendas con esos filtros.</p>
        <a href="<?= e(url('admin/producto_form.php')) ?>" class="<?= CLS_BTN ?>">Agregar la primera prenda</a>
    </div>
<?php else: ?>
    <ul class="space-y-3">
        <?php foreach ($productos as $p):
            $id = (int) $p['id'];
            $agot = (int) $p['stock_total'] <= 0;
            $vencida = $p['precio_oferta'] !== null && $p['oferta_hasta'] && $p['oferta_hasta'] < hoy(); ?>
            <li>
                <form method="post" id="f<?= $id ?>" class="<?= CLS_CARD ?> p-4 grid gap-4 lg:grid-cols-[72px_minmax(0,1.4fr)_120px_120px_150px_auto] items-center">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="accion" value="guardar_rapido">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="hidden" name="qs" value="<?= e($qs) ?>">

                    <a href="<?= e(url('admin/producto_form.php?id=' . $id)) ?>" class="group w-[72px] h-[72px] rounded-2xl overflow-hidden flex items-center justify-center" style="background-color: <?= e(tinte((string) $p['color_hex'])) ?>" aria-label="Editar <?= e($p['nombre']) ?>">
                        <?= imagen_producto($p, 'w-[75%] h-[75%]') ?>
                    </a>

                    <div class="min-w-0">
                        <a href="<?= e(url('admin/producto_form.php?id=' . $id)) ?>" class="font-semibold hover:text-marca block truncate"><?= e($p['nombre']) ?></a>
                        <p class="text-sm text-slate-500 truncate"><?= e($p['categoria_nombre'] ?? 'Sin categoría') ?></p>
                        <p class="mt-1.5 flex flex-wrap gap-1.5">
                            <?= $agot ? chip('Agotada', 'rojo') : chip((int) $p['stock_total'] . ' en stock', 'gris') ?>
                            <?php if (!empty($p['en_oferta'])): ?><?= chip('Oferta -' . Producto::porcentajeDescuento($p) . '%', 'ambar') ?><?php endif; ?>
                            <?php if ($vencida): ?><?= chip('Oferta vencida', 'gris') ?><?php endif; ?>
                            <?php if (!(int) $p['activo']): ?><?= chip('Oculta', 'gris') ?><?php endif; ?>
                        </p>
                    </div>

                    <div><label class="block text-xs font-bold tracking-wider text-slate-500 mb-1" for="pr<?= $id ?>">PRECIO $</label>
                        <input class="<?= $mini ?>" id="pr<?= $id ?>" name="precio" value="<?= e(rtrim(rtrim(number_format((float) $p['precio'], 2, '.', ''), '0'), '.')) ?>" inputmode="decimal" required></div>
                    <div><label class="block text-xs font-bold tracking-wider text-slate-500 mb-1" for="of<?= $id ?>">OFERTA $</label>
                        <input class="<?= $mini ?>" id="of<?= $id ?>" name="precio_oferta" value="<?= $p['precio_oferta'] !== null ? e(rtrim(rtrim(number_format((float) $p['precio_oferta'], 2, '.', ''), '0'), '.')) : '' ?>" inputmode="decimal" placeholder="Sin oferta"></div>
                    <div><label class="block text-xs font-bold tracking-wider text-slate-500 mb-1" for="ha<?= $id ?>">OFERTA HASTA</label>
                        <input class="<?= $mini ?>" id="ha<?= $id ?>" type="date" name="oferta_hasta" value="<?= e((string) $p['oferta_hasta']) ?>"></div>

                    <div class="flex lg:flex-col gap-3 lg:gap-2 items-start">
                        <div class="flex flex-col gap-1.5 text-sm">
                            <label class="flex items-center gap-2"><input type="checkbox" name="activo" value="1" class="w-4 h-4 accent-blue-500" <?= (int) $p['activo'] ? 'checked' : '' ?>> Visible</label>
                            <label class="flex items-center gap-2"><input type="checkbox" name="destacado" value="1" class="w-4 h-4 accent-blue-500" <?= (int) $p['destacado'] ? 'checked' : '' ?>> Destacada</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <button class="<?= CLS_BTN ?> !px-5 !py-2.5 text-sm">Guardar</button>
                            <button type="submit" form="del<?= $id ?>" class="text-sm text-slate-400 hover:text-rose-600 underline underline-offset-4" aria-label="Eliminar <?= e($p['nombre']) ?>">Eliminar</button>
                        </div>
                    </div>
                </form>
                <form method="post" id="del<?= $id ?>" data-confirmar="¿Eliminar «<?= e($p['nombre']) ?>»? Esta acción no se puede deshacer.">
                    <?= csrf_campo() ?><input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="qs" value="<?= e($qs) ?>">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($paginas > 1): ?>
        <nav class="flex justify-center items-center gap-3 mt-8" aria-label="Páginas">
            <?php if ($pagina > 1): ?><a class="<?= CLS_BTN_2 ?>" href="<?= e(url_con(['pagina' => $pagina - 1])) ?>">← Anterior</a><?php endif; ?>
            <span class="text-sm text-slate-500">Página <?= $pagina ?> de <?= $paginas ?></span>
            <?php if ($pagina < $paginas): ?><a class="<?= CLS_BTN_2 ?>" href="<?= e(url_con(['pagina' => $pagina + 1])) ?>">Siguiente →</a><?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
