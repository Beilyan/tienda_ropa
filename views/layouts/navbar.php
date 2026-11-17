<?php /* Barra de aviso, menú lateral, encabezado y mensajes. Recibe: $u, $unidades, $busqueda, $catsMenu, $mensajes. */ ?>
    <?php if (aj('aviso_activo') === '1' && trim(aj('aviso_texto')) !== ''): ?>
        <!-- BARRA DE AVISO (la edita el dueño en Promociones) -->
        <div class="bg-tinta text-white text-center text-sm py-2.5 px-4">
            <?php if (trim(aj('aviso_enlace')) !== ''): ?>
                <a href="<?= e(aj('aviso_enlace')) ?>" class="hover:underline"><?= e(aj('aviso_texto')) ?> <span aria-hidden="true">→</span></a>
            <?php else: ?>
                <?= e(aj('aviso_texto')) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- MENÚ LATERAL -->
    <div id="fondoMenu" class="fixed inset-0 bg-black/35 z-40 hidden" data-cerrar-menu></div>
    <aside id="menuLateral" role="dialog" aria-modal="true" aria-label="Menú principal"
           class="fixed top-0 left-0 h-screen w-full sm:w-[420px] bg-white z-50 -translate-x-full invisible transition-[transform,visibility] duration-300 flex flex-col overflow-y-auto">
        <div class="flex justify-between items-center px-8 py-8">
            <?= logo_tienda() ?>
            <button type="button" data-cerrar-menu aria-label="Cerrar menú"
                    class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 text-3xl hover:bg-slate-200 leading-none">×</button>
        </div>

        <nav class="px-8 mt-2" aria-label="Navegación">
            <ul class="text-lg">
                <?php
                $enlaces = [
                    ['principal.php', 'Inicio'],
                    ['lista_ropa.php', 'Todo el catálogo'],
                    ['lista_ropa.php?oferta=1', 'Ofertas'],
                    ['lista_favoritos.php', 'Mis favoritos'],
                    ['carrito.php', 'Carrito' . ($unidades ? " ($unidades)" : '')],
                ];
                if ($u) {
                    $enlaces[] = ['mis_pedidos.php', 'Mis pedidos'];
                    $enlaces[] = ['perfil.php', 'Mi perfil'];
                }
                foreach ($enlaces as [$href, $texto]): ?>
                    <li><a href="<?= e(url($href)) ?>" class="flex justify-between items-center py-4 border-b border-slate-100 hover:text-marca"><?= e($texto) ?><span class="text-slate-300" aria-hidden="true">→</span></a></li>
                <?php endforeach; ?>
            </ul>

            <?php $cats = $catsMenu; if ($cats): ?>
                <p class="text-xs font-bold tracking-[0.25em] text-marca mt-8 mb-3">CATEGORÍAS</p>
                <ul>
                    <?php foreach ($cats as $c): ?>
                        <li><a href="<?= e(url('lista_ropa.php?categoria=' . $c['slug'])) ?>" class="flex justify-between py-2.5 text-slate-600 hover:text-marca"><?= e($c['nombre']) ?><span class="text-slate-400 text-sm"><?= (int) $c['total'] ?></span></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </nav>

        <div class="mt-auto px-8 py-6 space-y-3">
            <?php if ($u && $u['rol'] === 'admin'): ?>
                <a href="<?= e(url('admin/')) ?>" class="flex justify-center items-center gap-2 bg-marca text-white font-semibold py-4 rounded-full hover:opacity-90">Ir al panel del dueño</a>
            <?php endif; ?>
            <?php if ($u): ?>
                <form method="post" action="<?= e(url('logout.php')) ?>">
                    <?= csrf_campo() ?>
                    <button type="submit" class="w-full flex justify-between items-center text-slate-500 hover:text-marca py-2">
                        Cerrar sesión (<?= e(strtok($u['nombre'], ' ')) ?>)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3m12-8h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-5"/></svg>
                    </button>
                </form>
            <?php else: ?>
                <a href="<?= e(url('login.php')) ?>" class="flex justify-between items-center text-slate-500 hover:text-marca py-2">Iniciar sesión o crear cuenta <span aria-hidden="true">→</span></a>
            <?php endif; ?>
        </div>
    </aside>

    <!-- ENCABEZADO -->
    <header class="bg-white/95 backdrop-blur border-b border-slate-200 px-4 sm:px-6 md:px-16 py-4 sticky top-0 z-30">
        <div class="flex items-center justify-between gap-3 sm:gap-5">
            <button type="button" data-abrir-menu aria-label="Abrir menú" aria-haspopup="dialog"
                    class="w-12 h-12 sm:w-14 sm:h-14 shrink-0 rounded-full border border-slate-200 flex items-center justify-center hover:bg-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <a href="<?= e(url('principal.php')) ?>" class="hidden sm:block shrink-0" aria-label="<?= e(aj('nombre_tienda')) ?>, ir al inicio"><?= logo_tienda() ?></a>

            <form action="<?= e(url('lista_ropa.php')) ?>" method="get" role="search" class="flex-1 max-w-2xl relative">
                <label for="busqueda-global" class="sr-only">Buscar prendas</label>
                <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m16 16 5 5"/></svg>
                <input id="busqueda-global" type="search" name="q" value="<?= e($busqueda) ?>" placeholder="Buscar prendas..." autocomplete="off"
                       class="w-full bg-slate-50 border border-slate-200 rounded-full py-3.5 sm:py-4 pl-12 pr-5 outline-none focus:border-marca focus:ring-2 focus:ring-marca/20">
            </form>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <a href="<?= e(url($u ? 'perfil.php' : 'login.php')) ?>" aria-label="<?= $u ? 'Mi perfil' : 'Iniciar sesión' ?>"
                   class="hidden sm:flex w-14 h-14 rounded-full border border-slate-200 items-center justify-center hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                </a>
                <a href="<?= e(url('carrito.php')) ?>" aria-label="Carrito, <?= $unidades ?> <?= $unidades === 1 ? 'artículo' : 'artículos' ?>"
                   class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-full border border-slate-200 flex items-center justify-center hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l-1 13H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                    <span class="absolute -top-1 -right-1 bg-marca text-white text-xs font-bold min-w-5 h-5 px-1 rounded-full flex items-center justify-center"><?= $unidades ?></span>
                </a>
            </div>
        </div>
    </header>

    <?php if ($mensajes): ?>
        <div class="px-5 md:px-[30px] pt-6 space-y-3" role="status" aria-live="polite">
            <?php foreach ($mensajes as $m):
                $estilo = ['ok' => 'bg-emerald-50 border-emerald-200 text-emerald-800', 'error' => 'bg-rose-50 border-rose-200 text-rose-800', 'info' => 'bg-blue-50 border-blue-200 text-blue-800'][$m['tipo']] ?? 'bg-blue-50 border-blue-200 text-blue-800'; ?>
                <p class="border rounded-2xl px-5 py-4 <?= $estilo ?>"><?= e($m['mensaje']) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
