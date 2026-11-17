<?php
/* Catálogo. Recibe: $titulo, $f (filtros), $categorias, $total, $paginas, $pagina, $productos, $hayFiltros. */
$pill = 'rounded-full border px-5 py-2.5 text-sm font-medium transition whitespace-nowrap ';
$pillOn = 'bg-marca border-marca text-white';
$pillOff = 'bg-white border-slate-200 hover:border-marca hover:text-marca';
$campo = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 outline-none focus:border-marca focus:ring-2 focus:ring-marca/20';
?>
<div class="flex flex-wrap justify-between items-end gap-4 mb-8">
    <div>
        <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4"><?= $f['q'] !== '' ? 'BÚSQUEDA: ' . e(mb_strtoupper($f['q'])) : 'ENCUENTRA TU PRENDA' ?></p>
        <h1 class="text-3xl md:text-5xl font-semibold tracking-tight"><?= e($titulo) ?></h1>
    </div>
    <span class="text-slate-400 text-sm" role="status"><?= $total ?> <?= $total === 1 ? 'prenda' : 'prendas' ?></span>
</div>

<!-- CATEGORÍAS RÁPIDAS -->
<nav aria-label="Categorías" class="flex gap-2 overflow-x-auto pb-2 mb-6">
    <a href="<?= e(url_con(['categoria' => '', 'pagina' => ''])) ?>" class="<?= $pill . ($f['categoria'] === '' ? $pillOn : $pillOff) ?>" <?= $f['categoria'] === '' ? 'aria-current="page"' : '' ?>>Todo</a>
    <?php foreach ($categorias as $c): $on = $c['slug'] === $f['categoria']; ?>
        <a href="<?= e(url_con(['categoria' => $c['slug'], 'pagina' => ''])) ?>" class="<?= $pill . ($on ? $pillOn : $pillOff) ?>" <?= $on ? 'aria-current="page"' : '' ?>><?= e($c['nombre']) ?></a>
    <?php endforeach; ?>
</nav>

<!-- FILTROS -->
<form method="get" class="bg-white border border-slate-200 rounded-[20px] p-5 mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-6 items-end">
    <?php if ($f['categoria'] !== ''): ?><input type="hidden" name="categoria" value="<?= e($f['categoria']) ?>"><?php endif; ?>
    <div class="lg:col-span-2">
        <label for="q" class="block text-xs font-bold tracking-widest text-slate-500 mb-2">BUSCAR</label>
        <input class="<?= $campo ?>" id="q" type="search" name="q" value="<?= e($f['q']) ?>" placeholder="Nombre, color o categoría">
    </div>
    <div>
        <label for="talla" class="block text-xs font-bold tracking-widest text-slate-500 mb-2">TALLA</label>
        <select class="<?= $campo ?>" id="talla" name="talla">
            <option value="">Todas</option>
            <?php foreach (TALLAS as $t): ?><option value="<?= e($t) ?>" <?= $f['talla'] === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?>
        </select>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <label for="min" class="block text-xs font-bold tracking-widest text-slate-500 mb-2">DESDE $</label>
            <input class="<?= $campo ?>" id="min" type="number" name="min" min="0" step="1" value="<?= e($f['min']) ?>" inputmode="numeric">
        </div>
        <div>
            <label for="max" class="block text-xs font-bold tracking-widest text-slate-500 mb-2">HASTA $</label>
            <input class="<?= $campo ?>" id="max" type="number" name="max" min="0" step="1" value="<?= e($f['max']) ?>" inputmode="numeric">
        </div>
    </div>
    <div>
        <label for="orden" class="block text-xs font-bold tracking-widest text-slate-500 mb-2">ORDENAR</label>
        <select class="<?= $campo ?>" id="orden" name="orden">
            <?php foreach (['nuevos' => 'Destacados y nuevos', 'precio_asc' => 'Precio: menor a mayor', 'precio_desc' => 'Precio: mayor a menor', 'nombre' => 'Nombre: A a la Z'] as $k => $t): ?>
                <option value="<?= $k ?>" <?= $f['orden'] === $k ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex items-center gap-4 sm:col-span-2 lg:col-span-1">
        <label class="flex items-center gap-2 text-sm text-slate-600 whitespace-nowrap"><input type="checkbox" name="oferta" value="1" class="w-5 h-5 accent-blue-500" <?= $f['oferta'] ? 'checked' : '' ?>> Solo ofertas</label>
        <button type="submit" class="bg-marca text-white font-semibold px-6 py-3 rounded-full hover:opacity-90 w-full sm:w-auto">Aplicar</button>
    </div>
    <?php if ($hayFiltros): ?>
        <div class="sm:col-span-2 lg:col-span-6"><a href="<?= e(url('lista_ropa.php')) ?>" class="text-sm text-marca font-semibold hover:underline">Quitar todos los filtros</a></div>
    <?php endif; ?>
</form>

<?php if ($productos): ?>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
        <?php foreach ($productos as $p) echo tarjeta_producto($p); ?>
    </div>

    <?php if ($paginas > 1): ?>
        <nav class="flex justify-center items-center gap-2 mt-12" aria-label="Páginas">
            <?php if ($pagina > 1): ?><a class="<?= $pill . $pillOff ?>" href="<?= e(url_con(['pagina' => $pagina - 1])) ?>">← Anterior</a><?php endif; ?>
            <span class="text-sm text-slate-500 px-3">Página <?= $pagina ?> de <?= $paginas ?></span>
            <?php if ($pagina < $paginas): ?><a class="<?= $pill . $pillOff ?>" href="<?= e(url_con(['pagina' => $pagina + 1])) ?>">Siguiente →</a><?php endif; ?>
        </nav>
    <?php endif; ?>
<?php else: ?>
    <div class="bg-white border border-slate-200 rounded-[30px] p-10 md:p-14 text-center max-w-xl mx-auto">
        <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">SIN RESULTADOS</p>
        <h2 class="text-2xl font-semibold mb-3">No encontramos prendas con esos filtros</h2>
        <p class="text-slate-500 mb-8">Prueba con otra palabra, otra talla o quita algún filtro.</p>
        <a href="<?= e(url('lista_ropa.php')) ?>" class="inline-block bg-marca text-white font-semibold px-7 py-4 rounded-full hover:opacity-90">Ver todo el catálogo</a>
    </div>
<?php endif; ?>
