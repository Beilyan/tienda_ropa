<?php
/* Tarjeta de producto. Recibe: $p (producto), $numero (posición opcional), $esFav (bool). */
$id = (int) $p['id'];
$agotado = (int) $p['stock_total'] <= 0;
$desc = Producto::porcentajeDescuento($p);
$nuevo = strtotime((string) $p['creado_en']) > strtotime('-14 days');

if ($agotado) {
    $insignia = '<span class="absolute top-4 left-4 z-10 bg-slate-800 text-white text-[11px] font-bold tracking-wider px-3 py-1 rounded-full">AGOTADO</span>';
} elseif ($desc > 0) {
    $insignia = '<span class="absolute top-4 left-4 z-10 bg-rose-500 text-white text-[11px] font-bold tracking-wider px-3 py-1 rounded-full">-' . $desc . '%</span>';
} elseif ($nuevo) {
    $insignia = '<span class="absolute top-4 left-4 z-10 bg-marca text-white text-[11px] font-bold tracking-wider px-3 py-1 rounded-full">NUEVO</span>';
} elseif ($numero > 0) {
    $insignia = '<span class="absolute top-5 left-5 text-xs tracking-widest text-slate-500">' . sprintf('%02d', $numero) . '</span>';
} else {
    $insignia = '';
}

$precio = '<span class="text-marca font-bold text-lg">' . e(dinero($p['precio_final'])) . '</span>';
if (!empty($p['en_oferta'])) {
    $precio .= '<span class="text-slate-400 text-sm line-through ml-2">' . e(dinero($p['precio'])) . '</span>';
}
?>
    <article class="group relative">
        <a href="<?= e(url('producto.php?id=' . $id)) ?>" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-marca rounded-[20px]">
            <div class="relative aspect-square rounded-[20px] flex items-center justify-center overflow-hidden <?= $agotado ? 'opacity-70' : '' ?>"
                 style="background-color: <?= e(tinte((string) $p['color_hex'])) ?>">
                <?= $insignia ?>
                <?= imagen_producto($p) ?>
            </div>
            <div class="pt-5 px-1">
                <div class="flex justify-between items-center text-sm mb-2 gap-3">
                    <span class="text-slate-400 truncate"><?= e($p['categoria_nombre'] ?? 'Prendas') ?></span>
                    <span class="shrink-0"><?= $precio ?></span>
                </div>
                <h3 class="font-semibold text-base md:text-lg leading-snug"><?= e($p['nombre']) ?></h3>
            </div>
        </a>
        <form method="post" action="<?= e(url('favorito.php')) ?>" data-favorito class="absolute top-3 right-3 z-10">
            <?= csrf_campo() ?>
            <input type="hidden" name="producto_id" value="<?= $id ?>">
            <button type="submit"
                    aria-label="<?= $esFav ? 'Quitar de favoritos' : 'Guardar en favoritos' ?>: <?= e($p['nombre']) ?>"
                    aria-pressed="<?= $esFav ? 'true' : 'false' ?>"
                    class="group/fav w-10 h-10 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-slate-400 hover:text-rose-500 aria-pressed:text-rose-500 transition">
                <svg class="w-5 h-5 group-aria-pressed/fav:fill-current" viewBox="0 0 24 24" fill="<?= $esFav ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" data-corazon>
                    <path d="M12 21s-7-4.6-9.5-9.2C.9 8.6 2.6 5 6 5c2 0 3.2 1.1 4 2.3C10.8 6.1 12 5 14 5c3.4 0 5.1 3.6 3.5 6.8C19 16.4 12 21 12 21Z"/>
                </svg>
            </button>
        </form>
    </article>
