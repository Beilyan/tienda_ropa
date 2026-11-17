<?php /* Inicio. Recibe: $banners, $ofertas, $novedades, $categorias, $totalPrendas. */ ?>

<!-- BANNER PRINCIPAL (carrusel si hay más de uno) -->
<section id="colecciones" aria-label="Promociones" data-carrusel class="relative">
    <?php foreach ($banners as $i => $b): $fondo = color_valido($b['color_fondo']); ?>
        <div data-slide class="<?= $i > 0 ? 'hidden' : '' ?> relative overflow-hidden rounded-[30px] min-h-[420px] md:min-h-[475px] flex items-center" style="background-color: <?= e($fondo) ?>">
            <!-- CÍRCULO DECORATIVO -->
            <div class="absolute w-[750px] h-[750px] border border-white/20 rounded-full -right-32 top-3" aria-hidden="true"></div>

            <div class="relative z-10 px-8 md:px-20 py-14 max-w-2xl">
                <?php if (!empty($b['etiqueta'])): ?>
                    <p class="text-white/80 font-bold tracking-[0.25em] text-xs md:text-sm mb-5"><?= e(mb_strtoupper($b['etiqueta'])) ?></p>
                <?php endif; ?>
                <h1 class="text-white text-4xl sm:text-5xl md:text-6xl font-bold tracking-tight leading-tight">
                    <?= e($b['titulo']) ?>
                    <?php if (!empty($b['titulo_cursiva'])): ?><br><span class="italic font-serif"><?= e($b['titulo_cursiva']) ?></span><?php endif; ?>
                </h1>
                <?php if (!empty($b['texto'])): ?>
                    <p class="text-white/85 text-lg mt-5 max-w-md"><?= e($b['texto']) ?></p>
                <?php endif; ?>
                <?php if (!empty($b['boton_texto'])): ?>
                    <a href="<?= e(preg_match('#^(https?:|/)#', (string) $b['boton_enlace']) ? $b['boton_enlace'] : url((string) ($b['boton_enlace'] ?: 'lista_ropa.php'))) ?>"
                       class="inline-block mt-9 bg-white font-semibold px-7 py-4 rounded-xl hover:bg-blue-50 transition duration-300"
                       style="color: <?= e($fondo) ?>"><?= e($b['boton_texto']) ?></a>
                <?php endif; ?>
            </div>

            <div class="absolute right-0 md:right-16 bottom-0 w-[45%] h-full hidden sm:flex items-center justify-center" aria-hidden="true">
                <?php if (!empty($b['imagen'])): ?>
                    <img src="<?= e(url_imagen($b['imagen'])) ?>" alt="" class="max-h-[86%] max-w-full object-contain rounded-3xl">
                <?php else: ?>
                    <!-- ILUSTRACIÓN DE GANCHO (la de principal.php) -->
                    <svg viewBox="0 0 500 450" class="w-full max-w-[480px] h-full" fill="none">
                        <path d="M250 35 C250 -10 320 -10 320 40 C320 85 265 105 250 145" stroke="white" stroke-width="6" stroke-linecap="round"/>
                        <path d="M250 145 L90 275 C65 295 85 325 115 322 L385 305 C420 303 430 275 405 257 L250 145Z" stroke="white" stroke-width="6" stroke-linejoin="round"/>
                        <path d="M185 210 L315 200 L365 390 L155 390 Z" fill="#f8fafc"/>
                        <path d="M185 210 Q250 300 315 200" stroke="<?= e($fondo) ?>" stroke-width="6"/>
                        <path d="M260 250 L270 390" stroke="#dbeafe" stroke-width="4"/>
                        <path d="M170 365 L350 350" stroke="<?= e($fondo) ?>" stroke-width="6"/>
                    </svg>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (count($banners) > 1): ?>
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex gap-2">
            <?php foreach ($banners as $i => $b): ?>
                <button type="button" data-punto aria-label="Ver promoción <?= $i + 1 ?> de <?= count($banners) ?>" aria-current="<?= $i === 0 ? 'true' : 'false' ?>"
                        class="w-3 h-3 rounded-full <?= $i === 0 ? 'bg-white' : 'bg-white/40' ?>"></button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php if ($categorias): ?>
<!-- CATEGORÍAS -->
<section class="mt-14" aria-labelledby="titulo-categorias">
    <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">EXPLORA POR TIPO</p>
    <h2 id="titulo-categorias" class="text-3xl md:text-4xl font-semibold tracking-tight mb-8">Categorías</h2>
    <ul class="flex gap-3 overflow-x-auto pb-2 -mx-1 px-1">
        <?php foreach ($categorias as $c): ?>
            <li class="shrink-0">
                <a href="<?= e(url('lista_ropa.php?categoria=' . $c['slug'])) ?>"
                   class="flex items-center gap-3 bg-white border border-slate-200 rounded-full pl-2 pr-6 py-2 hover:border-marca hover:text-marca transition">
                    <span class="w-11 h-11 rounded-full bg-marca/10 flex items-center justify-center"><?= ilustracion_prenda($c['icono'], color_valido(aj('color_marca')), 'w-7 h-7') ?></span>
                    <span class="font-medium"><?= e($c['nombre']) ?></span>
                    <span class="text-slate-400 text-sm"><?= (int) $c['total'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>

<?php if ($ofertas): ?>
<!-- OFERTAS (aparece solo si hay ofertas vigentes) -->
<section id="ofertas" class="mt-16" aria-labelledby="titulo-ofertas">
    <div class="flex justify-between items-end mb-8">
        <div>
            <p class="text-rose-500 text-xs font-bold tracking-[0.25em] mb-4">POR TIEMPO LIMITADO</p>
            <h2 id="titulo-ofertas" class="text-3xl md:text-4xl font-semibold tracking-tight">Ofertas</h2>
        </div>
        <a href="<?= e(url('lista_ropa.php?oferta=1')) ?>" class="text-marca font-semibold hover:underline">Ver todas →</a>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
        <?php foreach ($ofertas as $p) echo tarjeta_producto($p); ?>
    </div>
</section>
<?php endif; ?>

<!-- NOVEDADES -->
<section id="novedades" class="mt-16" aria-labelledby="titulo-novedades">
    <div class="flex justify-between items-end mb-8">
        <div>
            <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">SELECCIÓN PARA TI</p>
            <h2 id="titulo-novedades" class="text-3xl md:text-4xl font-semibold tracking-tight">Novedades</h2>
        </div>
        <span class="text-slate-400 text-sm"><?= $totalPrendas ?> <?= $totalPrendas === 1 ? 'prenda' : 'prendas' ?></span>
    </div>

    <?php if ($novedades): ?>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
            <?php foreach ($novedades as $i => $p) echo tarjeta_producto($p, $i + 1); ?>
        </div>
        <div class="text-center mt-12">
            <a href="<?= e(url('lista_ropa.php')) ?>" class="inline-block border border-slate-300 bg-white font-semibold px-8 py-4 rounded-full hover:border-marca hover:text-marca transition">Ver todo el catálogo</a>
        </div>
    <?php else: ?>
        <div class="bg-white border border-slate-200 rounded-[20px] p-10 text-center text-slate-500">
            Muy pronto tendremos prendas nuevas. Vuelve a visitarnos.
        </div>
    <?php endif; ?>
</section>

<!-- BENEFICIOS (los textos se editan en Ajustes) -->
<section class="mt-16 grid gap-5 md:grid-cols-3" aria-label="Beneficios">
    <?php foreach ([1, 2, 3] as $n): if (trim(aj("beneficio{$n}_titulo")) === '') continue; ?>
        <div class="bg-white border border-slate-200 rounded-[20px] p-7">
            <p class="text-marca text-xs font-bold tracking-[0.25em] mb-3"><?= sprintf('%02d', $n) ?></p>
            <h3 class="font-semibold text-lg mb-1"><?= e(aj("beneficio{$n}_titulo")) ?></h3>
            <p class="text-slate-500"><?= e(aj("beneficio{$n}_texto")) ?></p>
        </div>
    <?php endforeach; ?>
</section>
