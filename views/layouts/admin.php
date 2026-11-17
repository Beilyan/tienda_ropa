<?php
/* Layout del panel del dueño.
   Recibe: $titulo, $activo, $subtitulo, $accion (['href','texto','estilo']), $pendientes, $mensajes, $contenido. */
$icono = [
    'panel' => 'M4 13h6V4H4v9Zm0 7h6v-5H4v5Zm10 0h6V11h-6v9Zm0-16v5h6V4h-6Z',
    'productos' => 'M7 4 3 8v3h3v9h12v-9h3V8l-4-4-3 2a3 3 0 0 1-4 0L7 4Z',
    'categorias' => 'M4 5h7v7H4V5Zm9 0h7v7h-7V5ZM4 14h7v6H4v-6Zm9 0h7v6h-7v-6Z',
    'promociones' => 'M3 11v2l4 1 1 5h3l-1-4 9 4V6L8 10 3 11Z',
    'cupones' => 'M3 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4V8Zm6 0v8',
    'pedidos' => 'M5 8h14l-1 13H6L5 8Zm4 0V6a3 3 0 0 1 6 0v2',
    'ajustes' => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7.4-3a7.4 7.4 0 0 0-.1-1.2l2-1.5-2-3.4-2.4 1a7.6 7.6 0 0 0-2-1.2L14.5 3h-4l-.4 2.7a7.6 7.6 0 0 0-2 1.2l-2.4-1-2 3.4 2 1.5a7.4 7.4 0 0 0 0 2.4l-2 1.5 2 3.4 2.4-1a7.6 7.6 0 0 0 2 1.2l.4 2.7h4l.4-2.7a7.6 7.6 0 0 0 2-1.2l2.4 1 2-3.4-2-1.5c.1-.4.1-.8.1-1.2Z',
];
$menu = [
    'panel' => ['index.php', 'Panel'], 'productos' => ['productos.php', 'Productos y precios'],
    'categorias' => ['categorias.php', 'Categorías'], 'promociones' => ['promociones.php', 'Promociones y banners'],
    'cupones' => ['cupones.php', 'Cupones'], 'pedidos' => ['pedidos.php', 'Pedidos'], 'ajustes' => ['ajustes.php', 'Ajustes de la tienda'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($titulo) ?> | Panel de <?= e(aj('nombre_tienda')) ?></title>
    <?= tailwind_cdn() ?>
</head>
<body class="bg-fondo text-tinta antialiased">
<a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:bg-tinta focus:text-white focus:px-4 focus:py-2 focus:rounded-full">Saltar al contenido</a>

<!-- BARRA SUPERIOR EN CELULAR -->
<div class="lg:hidden sticky top-0 z-30 bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between">
    <button type="button" data-abrir-lateral aria-label="Abrir menú del panel" class="w-11 h-11 rounded-full border border-slate-200 flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
    <?= logo_tienda() ?>
    <span class="w-11"></span>
</div>
<div id="fondoLateral" class="fixed inset-0 bg-black/35 z-40 hidden lg:hidden" data-cerrar-lateral></div>

<!-- BARRA LATERAL -->
<aside id="lateral" class="fixed top-0 left-0 h-screen w-72 bg-white border-r border-slate-200 z-50 flex flex-col overflow-y-auto -translate-x-full invisible lg:visible lg:translate-x-0 transition-[transform,visibility] duration-300">
    <div class="px-7 py-8 flex items-center justify-between">
        <div>
            <?= logo_tienda() ?>
            <p class="text-xs font-bold tracking-[0.25em] text-marca mt-3 ml-12">PANEL DEL DUEÑO</p>
        </div>
        <button type="button" data-cerrar-lateral aria-label="Cerrar menú" class="lg:hidden w-10 h-10 rounded-full bg-slate-100 text-2xl leading-none">×</button>
    </div>
    <nav class="px-4 flex-1" aria-label="Panel">
        <ul class="space-y-1">
            <?php foreach ($menu as $clave => [$href, $texto]): $on = $clave === $activo; ?>
                <li>
                    <a href="<?= e(url('admin/' . $href)) ?>" <?= $on ? 'aria-current="page"' : '' ?>
                       class="flex items-center gap-3 rounded-full px-4 py-3 font-medium transition <?= $on ? 'bg-marca text-white' : 'text-slate-600 hover:bg-slate-100' ?>">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="<?= $icono[$clave] ?>"/></svg>
                        <span class="flex-1"><?= e($texto) ?></span>
                        <?php if ($clave === 'pedidos' && $pendientes > 0): ?>
                            <span class="min-w-6 h-6 px-1.5 rounded-full text-xs font-bold flex items-center justify-center <?= $on ? 'bg-white text-marca' : 'bg-rose-500 text-white' ?>" aria-label="<?= $pendientes ?> pendientes"><?= $pendientes ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="px-4 py-6 space-y-1 border-t border-slate-100">
        <a href="<?= e(url('principal.php')) ?>" target="_blank" rel="noopener" class="flex items-center justify-between rounded-full px-4 py-3 text-slate-600 hover:bg-slate-100">Ver mi tienda <span aria-hidden="true">↗</span></a>
        <form method="post" action="<?= e(url('admin/logout.php')) ?>">
            <?= csrf_campo() ?>
            <button class="w-full text-left rounded-full px-4 py-3 text-slate-500 hover:bg-slate-100 hover:text-rose-600">Cerrar sesión</button>
        </form>
    </div>
</aside>

<div class="lg:pl-72">
    <main id="contenido" class="px-5 md:px-10 py-8 md:py-10 max-w-6xl">
        <header class="flex flex-wrap items-end justify-between gap-4 mb-8">
            <div>
                <?php if (($subtitulo ?? '') !== ''): ?><p class="text-marca text-xs font-bold tracking-[0.25em] mb-3"><?= e(mb_strtoupper($subtitulo)) ?></p><?php endif; ?>
                <h1 class="text-3xl md:text-4xl font-semibold tracking-tight"><?= e($titulo) ?></h1>
            </div>
            <?php if (!empty($accion)): ?>
                <a href="<?= e(url($accion['href'])) ?>" class="<?= ($accion['estilo'] ?? 'primario') === 'secundario' ? CLS_BTN_2 : CLS_BTN ?>"><?= e($accion['texto']) ?></a>
            <?php endif; ?>
        </header>

        <?php if ($mensajes): ?>
            <div class="space-y-3 mb-8" role="status" aria-live="polite">
                <?php foreach ($mensajes as $m):
                    $estilo = ['ok' => 'bg-emerald-50 border-emerald-200 text-emerald-800', 'error' => 'bg-rose-50 border-rose-200 text-rose-800', 'info' => 'bg-blue-50 border-blue-200 text-blue-800'][$m['tipo']] ?? 'bg-blue-50 border-blue-200 text-blue-800'; ?>
                    <p class="border rounded-2xl px-5 py-4 <?= $estilo ?>"><?= e($m['mensaje']) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
<?= $contenido ?>
    </main>
</div>
<script src="<?= e(url('assets/admin.js')) ?>" defer></script>
</body>
</html>
