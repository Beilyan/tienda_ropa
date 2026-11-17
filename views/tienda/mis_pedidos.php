<?php
/* Mis pedidos. Recibe: $pedidos (cada uno con "items"). */
$colores = [
    'pendiente' => 'bg-amber-50 text-amber-700 border-amber-200', 'confirmado' => 'bg-blue-50 text-blue-700 border-blue-200',
    'enviado' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'entregado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    'cancelado' => 'bg-slate-100 text-slate-500 border-slate-200',
];
?>
<div class="mb-10">
    <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">HISTORIAL</p>
    <h1 class="text-3xl md:text-5xl font-semibold tracking-tight">Mis pedidos</h1>
</div>

<?php if (!$pedidos): ?>
    <div class="bg-white border border-slate-200 rounded-[30px] p-10 md:p-14 text-center max-w-xl mx-auto">
        <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">SIN PEDIDOS</p>
        <h2 class="text-2xl font-semibold mb-3">Aún no has comprado nada</h2>
        <p class="text-slate-500 mb-8">Cuando hagas tu primer pedido, lo verás aquí con su estado.</p>
        <a href="<?= e(url('lista_ropa.php')) ?>" class="inline-block bg-marca text-white font-semibold px-7 py-4 rounded-full hover:opacity-90">Ver el catálogo</a>
    </div>
<?php else: ?>
    <ul class="space-y-5">
        <?php foreach ($pedidos as $p): $items = $p['items']; ?>
            <li class="bg-white border border-slate-200 rounded-[20px] p-6">
                <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
                    <div><p class="font-semibold"><?= e($p['codigo']) ?></p><p class="text-sm text-slate-400"><?= e(fecha_corta($p['creado_en'])) ?> · <?= e(METODOS_PAGO[$p['metodo_pago']] ?? '') ?></p></div>
                    <span class="border rounded-full px-4 py-1.5 text-sm font-medium <?= $colores[$p['estado']] ?? '' ?>"><?= e(ESTADOS_PEDIDO[$p['estado']] ?? $p['estado']) ?></span>
                </div>
                <ul class="text-slate-600 space-y-1 mb-4">
                    <?php foreach ($items as $i): ?><li><?= (int) $i['cantidad'] ?> × <?= e($i['nombre']) ?> <span class="text-slate-400">· talla <?= e($i['talla']) ?></span></li><?php endforeach; ?>
                </ul>
                <p class="text-right font-bold text-lg">Total: <span class="text-marca"><?= e(dinero($p['total'])) ?></span></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
