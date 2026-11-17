<?php /* Panel. Recibe: $avisos, $tonos, $pendientes, $ventasMes, $pedidosMes, $activos, $agotados, $ultimos. */ ?>
<!-- ACCIONES RÁPIDAS -->
<section aria-label="Acciones rápidas" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-10">
    <?php foreach ([
        ['productos.php', 'Cambiar un precio', 'Edita precios y ofertas de un vistazo'],
        ['producto_form.php', 'Agregar una prenda', 'Con fotos, tallas y stock'],
        ['promociones.php', 'Crear promoción', 'Banner del inicio o barra de aviso'],
        ['pedidos.php', 'Ver pedidos', 'Confirma y da seguimiento'],
    ] as [$href, $t, $d]): ?>
        <a href="<?= e(url('admin/' . $href)) ?>" class="<?= CLS_CARD ?> p-6 hover:border-marca transition group">
            <p class="font-semibold text-lg group-hover:text-marca"><?= e($t) ?> <span aria-hidden="true">→</span></p>
            <p class="text-sm text-slate-500 mt-1"><?= e($d) ?></p>
        </a>
    <?php endforeach; ?>
</section>

<!-- CIFRAS -->
<section aria-label="Cifras" class="grid gap-4 grid-cols-2 lg:grid-cols-4 mb-10">
    <div class="<?= CLS_CARD ?> p-6"><p class="text-3xl font-bold text-marca"><?= $pendientes ?></p><p class="text-slate-500 text-sm mt-1">Pedidos pendientes</p></div>
    <div class="<?= CLS_CARD ?> p-6"><p class="text-3xl font-bold"><?= e(dinero($ventasMes)) ?></p><p class="text-slate-500 text-sm mt-1">Ventas del mes (<?= $pedidosMes ?> <?= $pedidosMes === 1 ? 'pedido' : 'pedidos' ?>)</p></div>
    <div class="<?= CLS_CARD ?> p-6"><p class="text-3xl font-bold"><?= $activos ?></p><p class="text-slate-500 text-sm mt-1">Prendas visibles</p></div>
    <div class="<?= CLS_CARD ?> p-6"><p class="text-3xl font-bold <?= $agotados ? 'text-rose-500' : '' ?>"><?= count($agotados) ?></p><p class="text-slate-500 text-sm mt-1">Prendas agotadas</p></div>
</section>

<!-- REVISIÓN: que nada quede desactualizado -->
<section aria-labelledby="t-avisos" class="mb-10">
    <h2 id="t-avisos" class="text-xl font-semibold mb-4">Para revisar hoy</h2>
    <?php if ($avisos): ?>
        <ul class="space-y-3">
            <?php foreach ($avisos as [$tono, $texto, $href, $btn]): ?>
                <li class="border rounded-2xl px-5 py-4 flex flex-wrap items-center justify-between gap-3 <?= $tonos[$tono] ?>">
                    <span><?= e($texto) ?></span>
                    <a href="<?= e(url('admin/' . $href)) ?>" class="shrink-0 font-semibold text-marca hover:underline"><?= e($btn) ?> →</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="border border-emerald-200 bg-emerald-50 text-emerald-800 rounded-2xl px-5 py-4">Todo en orden: precios, ofertas, stock y banners están al día.</p>
    <?php endif; ?>
</section>

<!-- ÚLTIMOS PEDIDOS -->
<section aria-labelledby="t-pedidos">
    <div class="flex justify-between items-end mb-4">
        <h2 id="t-pedidos" class="text-xl font-semibold">Últimos pedidos</h2>
        <a href="<?= e(url('admin/pedidos.php')) ?>" class="text-marca font-semibold hover:underline">Ver todos →</a>
    </div>
    <?php if ($ultimos): ?>
        <div class="<?= CLS_CARD ?> divide-y divide-slate-100">
            <?php foreach ($ultimos as $p):
                $tono = ['pendiente' => 'ambar', 'confirmado' => 'azul', 'enviado' => 'indigo', 'entregado' => 'verde', 'cancelado' => 'gris'][$p['estado']] ?? 'gris'; ?>
                <a href="<?= e(url('admin/pedido.php?id=' . $p['id'])) ?>" class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 hover:bg-slate-50">
                    <span><span class="font-semibold"><?= e($p['codigo']) ?></span> <span class="text-slate-400">· <?= e($p['nombre']) ?> · <?= e(fecha_corta($p['creado_en'])) ?></span></span>
                    <span class="flex items-center gap-4"><?= chip(ESTADOS_PEDIDO[$p['estado']], $tono) ?><strong><?= e(dinero($p['total'])) ?></strong></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="<?= CLS_CARD ?> p-8 text-center text-slate-500">Todavía no hay pedidos. Cuando llegue el primero lo verás aquí.</p>
    <?php endif; ?>
</section>
