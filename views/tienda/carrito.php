<?php
/* Carrito. Recibe: $t (totales del carrito). */
$campo = 'w-full rounded-full border border-slate-200 bg-white px-5 py-3 outline-none focus:border-marca focus:ring-2 focus:ring-marca/20';
?>
<div class="mb-8">
    <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">TU COMPRA</p>
    <h1 class="text-3xl md:text-5xl font-semibold tracking-tight">Carrito</h1>
</div>

<?php if (!$t['lineas']): ?>
    <div class="bg-white border border-slate-200 rounded-[30px] p-10 md:p-14 text-center max-w-xl mx-auto">
        <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">CARRITO VACÍO</p>
        <h2 class="text-2xl font-semibold mb-3">Todavía no agregas nada</h2>
        <p class="text-slate-500 mb-8">Elige una prenda, selecciona tu talla y aparecerá aquí.</p>
        <a href="<?= e(url('lista_ropa.php')) ?>" class="inline-block bg-marca text-white font-semibold px-7 py-4 rounded-full hover:opacity-90">Ver el catálogo</a>
    </div>
<?php else: ?>
<div class="grid gap-8 lg:grid-cols-[1fr_400px] items-start">
    <ul class="space-y-4">
        <?php foreach ($t['lineas'] as $l): ?>
            <li class="bg-white border border-slate-200 rounded-[20px] p-4 flex gap-4 sm:gap-6">
                <a href="<?= e(url('producto.php?id=' . $l['id'])) ?>" class="group shrink-0 w-28 sm:w-36 aspect-square rounded-2xl flex items-center justify-center overflow-hidden" style="background-color: <?= e(tinte((string) $l['color_hex'])) ?>">
                    <?= imagen_producto($l, 'w-[75%] h-[75%]') ?>
                </a>
                <div class="flex-1 min-w-0 flex flex-col">
                    <div class="flex justify-between gap-3">
                        <div class="min-w-0">
                            <a href="<?= e(url('producto.php?id=' . $l['id'])) ?>" class="font-semibold text-lg hover:text-marca leading-snug block"><?= e($l['nombre']) ?></a>
                            <p class="text-sm text-slate-500 mt-1">Talla <?= e($l['talla']) ?> · <?= e(dinero($l['precio_final'])) ?> c/u
                                <?php if (!empty($l['en_oferta'])): ?><span class="text-rose-500 font-medium">en oferta</span><?php endif; ?></p>
                        </div>
                        <p class="font-bold text-lg text-marca shrink-0"><?= e(dinero($l['importe'])) ?></p>
                    </div>
                    <div class="mt-auto pt-4 flex items-center justify-between gap-3">
                        <form method="post" data-autoenviar>
                            <?= csrf_campo() ?>
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="clave" value="<?= e($l['clave']) ?>">
                            <div data-cantidad class="inline-flex items-center border border-slate-300 bg-white rounded-full">
                                <button type="button" data-paso="-1" aria-label="Menos unidades de <?= e($l['nombre']) ?>" class="w-10 h-10 text-lg hover:text-marca">−</button>
                                <label class="sr-only" for="c<?= e(md5($l['clave'])) ?>">Cantidad de <?= e($l['nombre']) ?>, talla <?= e($l['talla']) ?></label>
                                <input id="c<?= e(md5($l['clave'])) ?>" type="number" name="cantidad" value="<?= (int) $l['cantidad'] ?>" min="1" max="<?= min(20, (int) $l['stock']) ?>"
                                       class="w-10 text-center bg-transparent outline-none font-semibold [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" data-paso="1" aria-label="Más unidades de <?= e($l['nombre']) ?>" class="w-10 h-10 text-lg hover:text-marca">+</button>
                            </div>
                            <noscript><button class="ml-2 text-sm text-marca font-semibold">Actualizar</button></noscript>
                        </form>
                        <form method="post">
                            <?= csrf_campo() ?>
                            <input type="hidden" name="accion" value="quitar">
                            <input type="hidden" name="clave" value="<?= e($l['clave']) ?>">
                            <button type="submit" class="text-sm text-slate-500 hover:text-rose-600 underline underline-offset-4" aria-label="Quitar <?= e($l['nombre']) ?>, talla <?= e($l['talla']) ?>">Quitar</button>
                        </form>
                    </div>
                    <?php if ((int) $l['stock'] <= 3): ?><p class="text-xs text-rose-500 mt-2">Quedan solo <?= (int) $l['stock'] ?> piezas.</p><?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- RESUMEN -->
    <aside class="bg-white border border-slate-200 rounded-[30px] p-7 lg:sticky lg:top-28" aria-label="Resumen del pedido">
        <h2 class="text-xl font-semibold mb-6">Resumen</h2>

        <?php if ($t['falta_gratis'] > 0): $gd = (float) aj('envio_gratis_desde'); ?>
            <div class="mb-6">
                <p class="text-sm text-slate-600 mb-2">Te faltan <strong><?= e(dinero($t['falta_gratis'])) ?></strong> para el envío gratis.</p>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-marca rounded-full" style="width: <?= (int) min(100, $t['subtotal'] / $gd * 100) ?>%"></div></div>
            </div>
        <?php elseif ((float) aj('costo_envio') > 0 && (float) aj('envio_gratis_desde') > 0): ?>
            <p class="mb-6 text-sm bg-emerald-50 text-emerald-700 rounded-2xl px-4 py-3">¡Tu pedido tiene envío gratis!</p>
        <?php endif; ?>

        <dl class="space-y-3 text-slate-600">
            <div class="flex justify-between"><dt>Subtotal (<?= (int) $t['unidades'] ?> <?= $t['unidades'] === 1 ? 'prenda' : 'prendas' ?>)</dt><dd><?= e(dinero($t['subtotal'])) ?></dd></div>
            <?php if ($t['descuento'] > 0): ?>
                <div class="flex justify-between text-emerald-600"><dt>Cupón <?= e($t['cupon']['codigo']) ?></dt><dd>−<?= e(dinero($t['descuento'])) ?></dd></div>
            <?php endif; ?>
            <div class="flex justify-between"><dt>Envío</dt><dd><?= $t['envio'] > 0 ? e(dinero($t['envio'])) : 'Gratis' ?></dd></div>
            <div class="flex justify-between items-baseline border-t border-slate-200 pt-4 text-tinta">
                <dt class="font-semibold">Total</dt><dd class="text-2xl font-bold"><?= e(dinero($t['total'])) ?> <span class="text-xs font-normal text-slate-400">MXN</span></dd>
            </div>
        </dl>

        <!-- CUPÓN -->
        <?php if ($t['cupon']): ?>
            <form method="post" class="mt-5">
                <?= csrf_campo() ?><input type="hidden" name="accion" value="quitar_cupon">
                <button class="text-sm text-slate-500 hover:text-rose-600 underline underline-offset-4">Quitar cupón <?= e($t['cupon']['codigo']) ?></button>
            </form>
        <?php else: ?>
            <form method="post" class="mt-6 flex gap-2">
                <?= csrf_campo() ?><input type="hidden" name="accion" value="cupon">
                <label class="sr-only" for="codigo">Código de cupón</label>
                <input class="<?= $campo ?> uppercase" id="codigo" name="codigo" placeholder="Código de cupón" maxlength="30" autocomplete="off">
                <button class="shrink-0 border border-slate-300 rounded-full px-5 font-semibold hover:border-marca hover:text-marca">Aplicar</button>
            </form>
        <?php endif; ?>

        <a href="<?= e(url('checkout.php')) ?>" class="mt-6 flex justify-center bg-marca text-white font-semibold px-7 py-4 rounded-full hover:opacity-90 transition">Continuar con el pedido</a>
        <a href="<?= e(url('lista_ropa.php')) ?>" class="mt-3 block text-center text-sm text-slate-500 hover:text-marca">Seguir comprando</a>
    </aside>
</div>
<?php endif; ?>
