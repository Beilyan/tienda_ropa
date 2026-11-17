<?php
/* Finalizar pedido. Recibe: $t, $v, $errores, $metodos, $descripciones. */
$campo = 'w-full rounded-xl border bg-white px-4 py-3.5 outline-none focus:border-marca focus:ring-2 focus:ring-marca/20 ';
$cls = fn(string $k) => $campo . (isset($errores[$k]) ? 'border-rose-400' : 'border-slate-200');
/** Mensaje de error bajo un campo. */
$err = function (string $k) use ($errores): void {
    if (isset($errores[$k])) {
        echo '<p class="text-sm text-rose-600 mt-1.5" role="alert">' . e($errores[$k]) . '</p>';
    }
};
?>
<div class="mb-8">
    <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">ÚLTIMO PASO</p>
    <h1 class="text-3xl md:text-5xl font-semibold tracking-tight">Finalizar pedido</h1>
</div>

<?php if ($errores): ?><p role="alert" class="mb-6 border border-rose-200 bg-rose-50 text-rose-800 rounded-2xl px-5 py-4">Revisa los campos marcados para continuar.</p><?php endif; ?>

<form method="post" class="grid gap-8 lg:grid-cols-[1fr_400px] items-start" novalidate>
    <?= csrf_campo() ?>
    <div class="space-y-8">
        <section class="bg-white border border-slate-200 rounded-[30px] p-6 md:p-8">
            <h2 class="text-xl font-semibold mb-6">Datos de entrega</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2"><label class="block text-sm font-medium mb-2" for="nombre">Nombre completo</label>
                    <input class="<?= $cls('nombre') ?>" id="nombre" name="nombre" value="<?= e($v['nombre']) ?>" autocomplete="name" required><?php $err('nombre'); ?></div>
                <div><label class="block text-sm font-medium mb-2" for="email">Correo electrónico</label>
                    <input class="<?= $cls('email') ?>" id="email" type="email" name="email" value="<?= e($v['email']) ?>" autocomplete="email" required><?php $err('email'); ?></div>
                <div><label class="block text-sm font-medium mb-2" for="telefono">Teléfono / WhatsApp</label>
                    <input class="<?= $cls('telefono') ?>" id="telefono" type="tel" name="telefono" value="<?= e($v['telefono']) ?>" autocomplete="tel" required><?php $err('telefono'); ?></div>
                <div class="sm:col-span-2"><label class="block text-sm font-medium mb-2" for="direccion">Dirección (calle, número, colonia)</label>
                    <input class="<?= $cls('direccion') ?>" id="direccion" name="direccion" value="<?= e($v['direccion']) ?>" autocomplete="street-address" required><?php $err('direccion'); ?></div>
                <div><label class="block text-sm font-medium mb-2" for="ciudad">Ciudad</label>
                    <input class="<?= $cls('ciudad') ?>" id="ciudad" name="ciudad" value="<?= e($v['ciudad']) ?>" autocomplete="address-level2" required><?php $err('ciudad'); ?></div>
                <div><label class="block text-sm font-medium mb-2" for="cp">Código postal</label>
                    <input class="<?= $cls('cp') ?>" id="cp" name="cp" value="<?= e($v['cp']) ?>" autocomplete="postal-code" inputmode="numeric" required><?php $err('cp'); ?></div>
                <div class="sm:col-span-2"><label class="block text-sm font-medium mb-2" for="notas">Notas para la entrega (opcional)</label>
                    <textarea class="<?= $cls('notas') ?>" id="notas" name="notas" rows="3" maxlength="500"><?= e($v['notas']) ?></textarea></div>
            </div>
        </section>

        <section class="bg-white border border-slate-200 rounded-[30px] p-6 md:p-8">
            <h2 class="text-xl font-semibold mb-2">¿Cómo quieres pagar?</h2>
            <p class="text-sm text-slate-500 mb-6">Por ahora no cobramos con tarjeta en línea; el pago se realiza de la forma que elijas aquí.</p>
            <fieldset class="space-y-3">
                <legend class="sr-only">Método de pago</legend>
                <?php foreach ($metodos as $clave => $nombre): ?>
                    <label class="flex items-start gap-4 border border-slate-200 rounded-2xl p-4 cursor-pointer hover:border-marca has-[:checked]:border-marca has-[:checked]:bg-marca/5">
                        <input type="radio" name="metodo" value="<?= e($clave) ?>" class="mt-1 w-5 h-5 accent-blue-500" <?= $v['metodo'] === $clave ? 'checked' : '' ?>>
                        <span><span class="font-semibold block"><?= e($nombre) ?></span><span class="text-sm text-slate-500"><?= e($descripciones[$clave] ?? '') ?></span></span>
                    </label>
                <?php endforeach; ?>
                <?php $err('metodo'); ?>
            </fieldset>
        </section>
    </div>

    <aside class="bg-white border border-slate-200 rounded-[30px] p-7 lg:sticky lg:top-28" aria-label="Resumen del pedido">
        <h2 class="text-xl font-semibold mb-5">Tu pedido</h2>
        <ul class="space-y-4 mb-6 max-h-72 overflow-y-auto pr-1">
            <?php foreach ($t['lineas'] as $l): ?>
                <li class="flex items-center gap-4">
                    <span class="shrink-0 w-16 h-16 rounded-xl flex items-center justify-center overflow-hidden" style="background-color: <?= e(tinte((string) $l['color_hex'])) ?>"><?= imagen_producto($l, 'w-[80%] h-[80%]') ?></span>
                    <span class="flex-1 min-w-0"><span class="block font-medium truncate"><?= e($l['nombre']) ?></span><span class="text-sm text-slate-500">Talla <?= e($l['talla']) ?> × <?= (int) $l['cantidad'] ?></span></span>
                    <span class="font-semibold"><?= e(dinero($l['importe'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <dl class="space-y-3 text-slate-600 border-t border-slate-200 pt-5">
            <div class="flex justify-between"><dt>Subtotal</dt><dd><?= e(dinero($t['subtotal'])) ?></dd></div>
            <?php if ($t['descuento'] > 0): ?><div class="flex justify-between text-emerald-600"><dt>Cupón <?= e($t['cupon']['codigo']) ?></dt><dd>−<?= e(dinero($t['descuento'])) ?></dd></div><?php endif; ?>
            <div class="flex justify-between"><dt>Envío</dt><dd><?= $t['envio'] > 0 ? e(dinero($t['envio'])) : 'Gratis' ?></dd></div>
            <div class="flex justify-between items-baseline border-t border-slate-200 pt-4 text-tinta"><dt class="font-semibold">Total</dt><dd class="text-2xl font-bold"><?= e(dinero($t['total'])) ?> <span class="text-xs font-normal text-slate-400">MXN</span></dd></div>
        </dl>
        <button type="submit" class="mt-6 w-full bg-marca text-white font-semibold px-7 py-4 rounded-full hover:opacity-90 transition">Confirmar pedido</button>
        <a href="<?= e(url('carrito.php')) ?>" class="mt-3 block text-center text-sm text-slate-500 hover:text-marca">Volver al carrito</a>
    </aside>
</form>
