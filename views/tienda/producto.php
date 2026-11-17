<?php /* Ficha de producto. Recibe: $p, $id, $tallas, $agotado, $desc, $esFav, $relacionados. */ ?>
<nav aria-label="Ruta" class="text-sm text-slate-400 mb-6">
    <a href="<?= e(url('principal.php')) ?>" class="hover:text-marca">Inicio</a> <span aria-hidden="true">/</span>
    <a href="<?= e(url('lista_ropa.php')) ?>" class="hover:text-marca">Catálogo</a>
    <?php if (!empty($p['categoria_nombre'])): ?>
        <span aria-hidden="true">/</span> <a href="<?= e(url('lista_ropa.php?categoria=' . $p['categoria_slug'])) ?>" class="hover:text-marca"><?= e($p['categoria_nombre']) ?></a>
    <?php endif; ?>
</nav>

<div class="grid gap-8 lg:gap-14 lg:grid-cols-2 items-start">
    <!-- IMAGEN -->
    <div class="group relative aspect-square rounded-[30px] flex items-center justify-center overflow-hidden" style="background-color: <?= e(tinte((string) $p['color_hex'])) ?>">
        <?php if ($desc > 0 && !$agotado): ?><span class="absolute top-5 left-5 z-10 bg-rose-500 text-white text-xs font-bold tracking-wider px-4 py-1.5 rounded-full">-<?= $desc ?>%</span><?php endif; ?>
        <?php if ($agotado): ?><span class="absolute top-5 left-5 z-10 bg-slate-800 text-white text-xs font-bold tracking-wider px-4 py-1.5 rounded-full">AGOTADO</span><?php endif; ?>
        <?= imagen_producto($p, 'w-[70%] h-[70%]') ?>
    </div>

    <!-- INFORMACIÓN -->
    <div>
        <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4"><?= e(mb_strtoupper($p['categoria_nombre'] ?? 'PRENDAS')) ?></p>
        <h1 class="text-3xl md:text-5xl font-semibold tracking-tight leading-tight"><?= e($p['nombre']) ?></h1>

        <p class="mt-5 flex items-baseline flex-wrap gap-3">
            <span class="text-3xl font-bold text-marca"><?= e(dinero($p['precio_final'])) ?></span>
            <?php if (!empty($p['en_oferta'])): ?><span class="text-lg text-slate-400 line-through"><?= e(dinero($p['precio'])) ?></span><?php endif; ?>
            <span class="text-xs text-slate-400">MXN</span>
        </p>
        <?php if (!empty($p['en_oferta']) && $p['oferta_hasta']): ?>
            <p class="mt-2 text-sm text-rose-600 font-medium">Oferta válida hasta el <?= e(fecha_corta($p['oferta_hasta'])) ?>.</p>
        <?php endif; ?>

        <?php if (trim((string) $p['descripcion']) !== ''): ?>
            <p class="mt-6 text-slate-600 leading-relaxed whitespace-pre-line max-w-xl"><?= e($p['descripcion']) ?></p>
        <?php endif; ?>

        <?php if (!empty($p['color_nombre'])): ?>
            <p class="mt-6 flex items-center gap-3 text-sm text-slate-600">
                <span class="w-6 h-6 rounded-full border border-slate-300" style="background-color: <?= e($p['color_hex']) ?>" aria-hidden="true"></span>
                Color: <strong class="text-tinta"><?= e($p['color_nombre']) ?></strong>
            </p>
        <?php endif; ?>

        <form method="post" action="<?= e(url('carrito.php')) ?>" class="mt-8">
            <?= csrf_campo() ?>
            <input type="hidden" name="accion" value="agregar">
            <input type="hidden" name="producto_id" value="<?= $id ?>">

            <fieldset>
                <legend class="text-xs font-bold tracking-widest text-slate-500 mb-3">ELIGE TU TALLA</legend>
                <div class="flex flex-wrap gap-3">
                    <?php foreach ($tallas as $t): $sin = (int) $t['stock'] <= 0; ?>
                        <label class="relative">
                            <input type="radio" name="talla" value="<?= e($t['talla']) ?>" data-stock="<?= (int) $t['stock'] ?>" class="peer sr-only" <?= $sin ? 'disabled' : 'required' ?>>
                            <span class="flex items-center justify-center min-w-14 h-14 px-4 rounded-full border border-slate-300 bg-white font-semibold cursor-pointer
                                         hover:border-marca peer-checked:bg-marca peer-checked:border-marca peer-checked:text-white
                                         peer-focus-visible:ring-2 peer-focus-visible:ring-marca peer-focus-visible:ring-offset-2
                                         peer-disabled:cursor-not-allowed peer-disabled:bg-slate-100 peer-disabled:text-slate-400 peer-disabled:line-through">
                                <?= e($t['talla']) ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                    <?php if (!$tallas): ?><p class="text-slate-500">Esta prenda aún no tiene tallas disponibles.</p><?php endif; ?>
                </div>
                <p id="aviso-talla" class="text-sm text-slate-500 mt-3 min-h-5" role="status" aria-live="polite"></p>
            </fieldset>

            <div class="mt-6 flex flex-wrap items-center gap-4">
                <div data-cantidad class="inline-flex items-center border border-slate-300 bg-white rounded-full">
                    <button type="button" data-paso="-1" aria-label="Menos" class="w-12 h-12 text-xl hover:text-marca">−</button>
                    <label class="sr-only" for="campo-cantidad">Cantidad</label>
                    <input id="campo-cantidad" type="number" name="cantidad" value="1" min="1" max="20" class="w-12 text-center bg-transparent outline-none font-semibold [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                    <button type="button" data-paso="1" aria-label="Más" class="w-12 h-12 text-xl hover:text-marca">+</button>
                </div>
                <button type="submit" <?= $agotado ? 'disabled' : '' ?>
                        class="flex-1 min-w-48 bg-marca text-white font-semibold px-8 py-4 rounded-full hover:opacity-90 transition disabled:bg-slate-300 disabled:cursor-not-allowed">
                    <?= $agotado ? 'Agotado por ahora' : 'Agregar al carrito' ?>
                </button>
            </div>
        </form>

        <form method="post" action="<?= e(url('favorito.php')) ?>" data-favorito class="mt-4">
            <?= csrf_campo() ?>
            <input type="hidden" name="producto_id" value="<?= $id ?>">
            <button type="submit" aria-pressed="<?= $esFav ? 'true' : 'false' ?>"
                    class="group/fav inline-flex items-center gap-2 text-slate-500 hover:text-rose-500 aria-pressed:text-rose-500">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="<?= $esFav ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" data-corazon aria-hidden="true"><path d="M12 21s-7-4.6-9.5-9.2C.9 8.6 2.6 5 6 5c2 0 3.2 1.1 4 2.3C10.8 6.1 12 5 14 5c3.4 0 5.1 3.6 3.5 6.8C19 16.4 12 21 12 21Z"/></svg>
                Guardar en favoritos
            </button>
        </form>

        <ul class="mt-8 border-t border-slate-200 pt-6 text-sm text-slate-500 space-y-2">
            <?php if ((float) aj('costo_envio') > 0 && (float) aj('envio_gratis_desde') > 0): ?>
                <li>Envío de <?= e(dinero(aj('costo_envio'))) ?>; gratis en compras desde <?= e(dinero(aj('envio_gratis_desde'))) ?>.</li>
            <?php elseif ((float) aj('costo_envio') <= 0): ?>
                <li>Envío gratis en todos los pedidos.</li>
            <?php endif; ?>
            <?php if (aj('beneficio2_titulo') !== '' || aj('beneficio2_texto') !== ''): ?>
                <li><?= e(trim(aj('beneficio2_titulo') . ': ' . aj('beneficio2_texto'), ': ')) ?></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php if ($relacionados): ?>
<section class="mt-20" aria-labelledby="titulo-relacionados">
    <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">TAMBIÉN TE PUEDE GUSTAR</p>
    <h2 id="titulo-relacionados" class="text-3xl font-semibold tracking-tight mb-8">Más prendas</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
        <?php foreach ($relacionados as $r) echo tarjeta_producto($r); ?>
    </div>
</section>
<?php endif; ?>
