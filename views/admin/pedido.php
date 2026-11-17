<?php /* Detalle de pedido. Recibe: $p, $items, $id, $tono, $tel, $msg, $final. */ ?>
<div class="grid lg:grid-cols-[1fr_340px] gap-8 items-start">
    <div class="space-y-6">
        <section class="<?= CLS_CARD ?> p-6" aria-labelledby="t-prendas">
            <h2 id="t-prendas" class="text-lg font-semibold mb-4">Prendas</h2>
            <ul class="divide-y divide-slate-100">
                <?php foreach ($items as $it): ?>
                    <li class="py-3 flex justify-between gap-4">
                        <span><?= e($it['nombre']) ?> <span class="text-slate-400">· Talla <?= e($it['talla']) ?> · <?= (int) $it['cantidad'] ?> × <?= e(dinero($it['precio'])) ?></span></span>
                        <strong><?= e(dinero((float) $it['precio'] * (int) $it['cantidad'])) ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
            <dl class="mt-4 pt-4 border-t border-slate-100 space-y-1.5 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Subtotal</dt><dd><?= e(dinero($p['subtotal'])) ?></dd></div>
                <?php if ((float) $p['descuento'] > 0): ?><div class="flex justify-between"><dt class="text-slate-500">Descuento <?= $p['cupon_codigo'] ? '(' . e($p['cupon_codigo']) . ')' : '' ?></dt><dd>−<?= e(dinero($p['descuento'])) ?></dd></div><?php endif; ?>
                <div class="flex justify-between"><dt class="text-slate-500">Envío</dt><dd><?= (float) $p['envio'] > 0 ? e(dinero($p['envio'])) : 'Gratis' ?></dd></div>
                <div class="flex justify-between text-lg font-bold pt-2"><dt>Total</dt><dd><?= e(dinero($p['total'])) ?></dd></div>
            </dl>
        </section>

        <section class="<?= CLS_CARD ?> p-6" aria-labelledby="t-cliente">
            <h2 id="t-cliente" class="text-lg font-semibold mb-4">Cliente y entrega</h2>
            <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                <div><dt class="text-slate-400">Nombre</dt><dd class="font-medium"><?= e($p['nombre']) ?></dd></div>
                <div><dt class="text-slate-400">Correo</dt><dd class="font-medium break-all"><a class="hover:text-marca" href="mailto:<?= e($p['email']) ?>"><?= e($p['email']) ?></a></dd></div>
                <div><dt class="text-slate-400">Teléfono</dt><dd class="font-medium"><?= e($p['telefono']) ?></dd></div>
                <div><dt class="text-slate-400">Forma de pago</dt><dd class="font-medium"><?= e(METODOS_PAGO[$p['metodo_pago']] ?? $p['metodo_pago']) ?></dd></div>
                <div class="sm:col-span-2"><dt class="text-slate-400">Dirección</dt><dd class="font-medium"><?= e($p['direccion']) ?>, <?= e($p['ciudad']) ?>, C.P. <?= e($p['cp']) ?></dd></div>
                <?php if ($p['notas']): ?><div class="sm:col-span-2"><dt class="text-slate-400">Notas del cliente</dt><dd class="font-medium"><?= e($p['notas']) ?></dd></div><?php endif; ?>
            </dl>
        </section>
    </div>

    <aside class="<?= CLS_CARD ?> p-6 space-y-5 lg:sticky lg:top-6" aria-labelledby="t-estado">
        <h2 id="t-estado" class="text-lg font-semibold">Estado del pedido</h2>
        <p><?= chip(ESTADOS_PEDIDO[$p['estado']], $tono) ?></p>
        <?php if ($final): ?>
            <p class="text-sm text-slate-500">Este pedido está cancelado y ya no se puede modificar.</p>
        <?php else: ?>
            <form method="post" class="space-y-3">
                <?= csrf_campo() ?><input type="hidden" name="id" value="<?= $id ?>">
                <label class="<?= CLS_LABEL ?>" for="estado">Cambiar a</label>
                <select class="<?= CLS_INPUT ?>" id="estado" name="estado">
                    <?php foreach (ESTADOS_PEDIDO as $k => $t): ?><option value="<?= $k ?>" <?= $p['estado'] === $k ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?>
                </select>
                <p class="<?= CLS_AYUDA ?>">«Cancelado» devuelve las piezas al inventario y no se puede deshacer.</p>
                <button class="<?= CLS_BTN ?> w-full">Guardar estado</button>
            </form>
        <?php endif; ?>
        <?php if ($tel !== ''): ?>
            <a href="https://wa.me/<?= e($tel) ?>?text=<?= $msg ?>" target="_blank" rel="noopener" class="<?= CLS_BTN_2 ?> w-full text-center block">Escribir por WhatsApp</a>
        <?php endif; ?>
    </aside>
</div>
