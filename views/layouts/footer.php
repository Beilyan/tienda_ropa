<?php /* Pie de página, botón de WhatsApp y scripts. */ $wa = solo_digitos(aj('whatsapp')); ?>
    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="bg-white border-t border-slate-200 mt-10">
        <div class="px-5 md:px-[30px] py-12 grid gap-10 md:grid-cols-3">
            <div>
                <?= logo_tienda() ?>
                <p class="text-slate-500 mt-4 max-w-xs"><?= e(aj('eslogan')) ?></p>
            </div>
            <div>
                <p class="text-xs font-bold tracking-[0.25em] text-marca mb-4">CONTACTO</p>
                <ul class="space-y-2 text-slate-600">
                    <?php if ($wa !== ''): ?><li><a class="hover:text-marca" href="https://wa.me/<?= e($wa) ?>" target="_blank" rel="noopener">WhatsApp: <?= e(aj('whatsapp')) ?></a></li><?php endif; ?>
                    <?php if (aj('telefono') !== ''): ?><li>Tel. <?= e(aj('telefono')) ?></li><?php endif; ?>
                    <?php if (aj('email_contacto') !== ''): ?><li><a class="hover:text-marca" href="mailto:<?= e(aj('email_contacto')) ?>"><?= e(aj('email_contacto')) ?></a></li><?php endif; ?>
                    <?php if (aj('direccion') !== ''): ?><li><?= e(aj('direccion')) ?></li><?php endif; ?>
                    <?php if (aj('horario') !== ''): ?><li><?= e(aj('horario')) ?></li><?php endif; ?>
                </ul>
            </div>
            <div>
                <p class="text-xs font-bold tracking-[0.25em] text-marca mb-4">EXPLORA</p>
                <ul class="space-y-2 text-slate-600">
                    <li><a class="hover:text-marca" href="<?= e(url('lista_ropa.php')) ?>">Catálogo</a></li>
                    <li><a class="hover:text-marca" href="<?= e(url('lista_ropa.php?oferta=1')) ?>">Ofertas</a></li>
                    <li><a class="hover:text-marca" href="<?= e(url('mis_pedidos.php')) ?>">Mis pedidos</a></li>
                    <?php if (aj('instagram') !== ''): ?><li><a class="hover:text-marca" href="<?= e(aj('instagram')) ?>" target="_blank" rel="noopener">Instagram</a></li><?php endif; ?>
                    <?php if (aj('facebook') !== ''): ?><li><a class="hover:text-marca" href="<?= e(aj('facebook')) ?>" target="_blank" rel="noopener">Facebook</a></li><?php endif; ?>
                </ul>
            </div>
        </div>
        <p class="border-t border-slate-100 text-center text-xs text-slate-400 py-5 px-5">
            © <?= date('Y') ?> <?= e(aj('nombre_tienda')) ?>. <?= e(aj('pie_texto')) ?>
        </p>
    </footer>

    <?php if ($wa !== ''): ?>
        <a href="https://wa.me/<?= e($wa) ?>" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp"
           class="fixed bottom-5 right-5 z-30 w-14 h-14 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg hover:bg-emerald-600">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm5.2 14c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.2-4.6-4-4.7-4.2-.1-.2-1.1-1.5-1.1-2.8s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 2c.1.2.1.4 0 .5l-.4.6-.3.4c-.1.1-.3.3-.1.6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.3 2.4 1.5.3.1.4.1.6-.1l.8-1c.2-.3.4-.2.6-.1l1.9.9c.3.1.5.2.5.3.1.2.1.8-.1 1.4Z"/></svg>
        </a>
    <?php endif; ?>

    <script src="<?= e(url('assets/tienda.js')) ?>" defer></script>
</body>
</html>
