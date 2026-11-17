<?php /* Favoritos. Recibe: $productos. */ ?>
<script>document.body.dataset.pagina = 'favoritos';</script>
<div class="flex flex-wrap justify-between items-end gap-4 mb-10">
    <div>
        <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">TU SELECCIÓN</p>
        <h1 class="text-3xl md:text-5xl font-semibold tracking-tight">Mis favoritos</h1>
    </div>
    <span class="text-slate-400 text-sm"><?= count($productos) ?> <?= count($productos) === 1 ? 'prenda' : 'prendas' ?></span>
</div>

<?php if ($productos): ?>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
        <?php foreach ($productos as $p) echo tarjeta_producto($p); ?>
    </div>
<?php else: ?>
    <div class="bg-white border border-slate-200 rounded-[30px] p-10 md:p-14 text-center max-w-xl mx-auto">
        <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">AÚN NO HAY FAVORITOS</p>
        <h2 class="text-2xl font-semibold mb-3">Guarda lo que más te guste</h2>
        <p class="text-slate-500 mb-8">Toca el corazón de cualquier prenda y aparecerá aquí.</p>
        <a href="<?= e(url('lista_ropa.php')) ?>" class="inline-block bg-marca text-white font-semibold px-7 py-4 rounded-full hover:opacity-90">Explorar el catálogo</a>
    </div>
<?php endif; ?>
