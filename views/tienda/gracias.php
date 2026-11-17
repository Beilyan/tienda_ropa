<?php /* Confirmación. Recibe: $pedido, $items, $u, $wa, $mensajeWa. */ ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-slate-200 rounded-[30px] p-8 md:p-12">
        <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">¡GRACIAS POR TU COMPRA!</p>
        <h1 class="text-3xl md:text-4xl font-semibold tracking-tight">Recibimos tu pedido</h1>
        <p class="text-slate-500 mt-3">Tu número de pedido es <strong class="text-tinta"><?= e($pedido['codigo']) ?></strong>. Te contactaremos al <?= e($pedido['telefono']) ?> o a <?= e($pedido['email']) ?>.</p>

        <div class="mt-8 rounded-2xl bg-slate-50 border border-slate-200 p-5">
            <p class="font-semibold mb-1"><?= e(METODOS_PAGO[$pedido['metodo_pago']] ?? '') ?></p>
            <?php if ($pedido['metodo_pago'] === 'contra_entrega'): ?>
                <p class="text-slate-600">Prepara <strong><?= e(dinero($pedido['total'])) ?></strong> en efectivo para cuando recibas tu pedido.</p>
            <?php elseif ($pedido['metodo_pago'] === 'transferencia'): ?>
                <p class="text-slate-600 mb-2">Transfiere <strong><?= e(dinero($pedido['total'])) ?></strong> y usa <strong><?= e($pedido['codigo']) ?></strong> como referencia:</p>
                <p class="whitespace-pre-line text-slate-700 bg-white border border-slate-200 rounded-xl p-4"><?= e(trim(aj('datos_transferencia')) !== '' ? aj('datos_transferencia') : 'Te enviaremos los datos bancarios en breve.') ?></p>
            <?php else: ?>
                <p class="text-slate-600">Escríbenos por WhatsApp para acordar el pago y la entrega.</p>
            <?php endif; ?>
            <?php if ($wa !== ''): ?>
                <a href="https://wa.me/<?= e($wa) ?>?text=<?= e(rawurlencode($mensajeWa)) ?>" target="_blank" rel="noopener"
                   class="inline-flex mt-4 items-center gap-2 bg-emerald-500 text-white font-semibold px-6 py-3 rounded-full hover:bg-emerald-600">Enviar mensaje por WhatsApp</a>
            <?php endif; ?>
        </div>

        <h2 class="font-semibold mt-10 mb-4">Resumen</h2>
        <ul class="divide-y divide-slate-100">
            <?php foreach ($items as $i): ?>
                <li class="py-3 flex justify-between gap-4"><span><?= e($i['nombre']) ?> <span class="text-slate-400">· talla <?= e($i['talla']) ?> × <?= (int) $i['cantidad'] ?></span></span><span><?= e(dinero($i['precio'] * $i['cantidad'])) ?></span></li>
            <?php endforeach; ?>
        </ul>
        <dl class="mt-4 space-y-2 text-slate-600 border-t border-slate-200 pt-4">
            <?php if ((float) $pedido['descuento'] > 0): ?><div class="flex justify-between"><dt>Descuento</dt><dd>−<?= e(dinero($pedido['descuento'])) ?></dd></div><?php endif; ?>
            <div class="flex justify-between"><dt>Envío</dt><dd><?= (float) $pedido['envio'] > 0 ? e(dinero($pedido['envio'])) : 'Gratis' ?></dd></div>
            <div class="flex justify-between text-tinta text-lg font-bold"><dt>Total</dt><dd><?= e(dinero($pedido['total'])) ?></dd></div>
        </dl>
        <p class="mt-6 text-sm text-slate-500">Enviaremos a: <?= e($pedido['direccion']) ?>, <?= e($pedido['ciudad']) ?>, CP <?= e($pedido['cp']) ?>.</p>

        <div class="mt-10 flex flex-wrap gap-3">
            <a href="<?= e(url('lista_ropa.php')) ?>" class="bg-marca text-white font-semibold px-7 py-4 rounded-full hover:opacity-90">Seguir comprando</a>
            <?php if ($u): ?><a href="<?= e(url('mis_pedidos.php')) ?>" class="border border-slate-300 font-semibold px-7 py-4 rounded-full hover:border-marca hover:text-marca">Ver mis pedidos</a><?php endif; ?>
        </div>
    </div>
</div>
