<?php /* Ajustes de la tienda y cuenta del dueño. Recibe: $v, $pagos, $errores, $cuenta, $logo. */
$cl = fn(string $k) => CLS_INPUT . (isset($errores[$k]) ? ' !border-rose-400' : '');
$err = fn(string $k) => isset($errores[$k]) ? '<p class="text-sm text-rose-600 mt-1.5" role="alert">' . e($errores[$k]) . '</p>' : '';
$campo = fn(string $k, string $et, string $extra = '', string $ayuda = '') =>
    '<div><label class="' . CLS_LABEL . '" for="' . $k . '">' . e($et) . '</label><input class="' . $cl($k) . '" id="' . $k . '" name="' . $k . '" value="' . e($v[$k]) . '" ' . $extra . '>' . $err($k) . ($ayuda ? '<p class="' . CLS_AYUDA . '">' . e($ayuda) . '</p>' : '') . '</div>';
$h2 = 'text-lg font-semibold mb-1';
?>
<?php if ($errores): ?><p role="alert" class="mb-6 border border-rose-200 bg-rose-50 text-rose-800 rounded-2xl px-5 py-4">Revisa los campos marcados en rojo.</p><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="space-y-8" novalidate>
    <?= csrf_campo() ?><input type="hidden" name="accion" value="tienda">

    <section class="<?= CLS_CARD ?> p-6 md:p-8" aria-labelledby="s-marca">
        <h2 id="s-marca" class="<?= $h2 ?>">Marca</h2>
        <p class="text-sm text-slate-500 mb-6">El nombre y el color se aplican en toda la tienda.</p>
        <div class="grid gap-5 sm:grid-cols-2">
            <?= $campo('nombre_tienda', 'Nombre de la tienda', 'maxlength="40" required') ?>
            <?= $campo('eslogan', 'Eslogan', 'maxlength="100"') ?>
            <div data-color-par><label class="<?= CLS_LABEL ?>" for="color_marca">Color principal</label>
                <div class="flex gap-3"><input type="color" value="<?= e($v['color_marca']) ?>" class="w-14 h-12 rounded-xl border border-slate-200 p-1 bg-white" aria-label="Elegir color"><input type="text" id="color_marca" name="color_marca" value="<?= e($v['color_marca']) ?>" maxlength="7" class="<?= $cl('color_marca') ?>"></div><?= $err('color_marca') ?></div>
            <?= $campo('pie_texto', 'Texto del pie de página', 'maxlength="120"') ?>
        </div>
        <div class="mt-6 pt-6 border-t border-slate-100">
            <label class="<?= CLS_LABEL ?>" for="logo">Logo (imagen con tu nombre)</label>
            <div class="flex flex-wrap items-center gap-4">
                <div id="vista-logo" data-contener class="min-w-40 h-20 px-4 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center text-xs text-slate-400 text-center"><?php if ($logo !== ''): ?><img src="<?= e(url_imagen($logo)) ?>" alt="Logo actual" class="max-h-16 w-auto max-w-[220px] object-contain"><?php else: ?>Sin logo: se usa el nombre de la tienda<?php endif; ?></div>
                <div class="flex-1 min-w-60">
                    <input class="<?= $cl('logo') ?> text-sm file:mr-3 file:rounded-full file:border-0 file:bg-marca/10 file:text-marca file:font-semibold file:px-4 file:py-2" id="logo" type="file" name="logo" accept="image/jpeg,image/png,image/webp" data-vista="vista-logo"><?= $err('logo') ?>
                    <p class="<?= CLS_AYUDA ?>">PNG con fondo transparente, JPG o WEBP, de hasta 5 MB. Mejor si es horizontal y se lee bien en tamaño pequeño. Reemplaza al cubo y al nombre en toda la tienda y en el panel.</p>
                    <?php if ($logo !== ''): ?><label class="flex items-center gap-2 text-sm mt-2"><input type="checkbox" name="quitar_logo" value="1" class="w-4 h-4 accent-blue-500"> Quitar el logo y volver al nombre de la tienda</label><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="<?= CLS_CARD ?> p-6 md:p-8" aria-labelledby="s-contacto">
        <h2 id="s-contacto" class="<?= $h2 ?>">Contacto</h2>
        <p class="text-sm text-slate-500 mb-6">Se muestra en el pie de página. El WhatsApp activa el botón flotante.</p>
        <div class="grid gap-5 sm:grid-cols-2">
            <?= $campo('whatsapp', 'WhatsApp (con lada)', 'inputmode="tel" maxlength="20" placeholder="5215512345678"', 'Solo números, con código de país.') ?>
            <?= $campo('telefono', 'Teléfono', 'inputmode="tel" maxlength="30"') ?>
            <?= $campo('email_contacto', 'Correo de contacto', 'type="email" maxlength="160"') ?>
            <?= $campo('horario', 'Horario de atención', 'maxlength="100" placeholder="Lun a Sáb, 10:00 a 19:00"') ?>
            <?= $campo('direccion', 'Dirección de la tienda', 'maxlength="200"') ?>
            <?= $campo('instagram', 'Instagram (enlace)', 'maxlength="200" placeholder="https://instagram.com/mitienda"') ?>
            <?= $campo('facebook', 'Facebook (enlace)', 'maxlength="200" placeholder="https://facebook.com/mitienda"') ?>
        </div>
    </section>

    <section class="<?= CLS_CARD ?> p-6 md:p-8" aria-labelledby="s-envio">
        <h2 id="s-envio" class="<?= $h2 ?>">Envíos y pagos</h2>
        <p class="text-sm text-slate-500 mb-6">Estos montos se usan en el carrito y al cobrar.</p>
        <div class="grid gap-5 sm:grid-cols-2">
            <?= $campo('costo_envio', 'Costo de envío ($)', 'inputmode="decimal"') ?>
            <?= $campo('envio_gratis_desde', 'Envío gratis desde ($)', 'inputmode="decimal"', 'Pon 0 si nunca quieres envío gratis.') ?>
        </div>
        <fieldset class="mt-6">
            <legend class="<?= CLS_LABEL ?>">Formas de pago que ofreces</legend>
            <div class="grid gap-3 sm:grid-cols-3">
                <?php foreach (['pago_contra_entrega' => 'Pago contra entrega', 'pago_transferencia' => 'Transferencia bancaria', 'pago_whatsapp' => 'Coordinar por WhatsApp'] as $k => $t): ?>
                    <label class="flex items-center gap-3 border border-slate-200 rounded-2xl px-4 py-3 has-[:checked]:border-marca has-[:checked]:bg-marca/5"><input type="checkbox" name="<?= $k ?>" value="1" class="w-5 h-5 accent-blue-500" <?= $pagos[$k] ? 'checked' : '' ?>> <?= e($t) ?></label>
                <?php endforeach; ?>
            </div><?= $err('pagos') ?>
        </fieldset>
        <div class="mt-5"><label class="<?= CLS_LABEL ?>" for="datos_transferencia">Datos para transferencia</label>
            <textarea class="<?= CLS_INPUT ?>" id="datos_transferencia" name="datos_transferencia" rows="3" maxlength="400" placeholder="Banco, titular, CLABE…"><?= e($v['datos_transferencia']) ?></textarea>
            <p class="<?= CLS_AYUDA ?>">Se le muestran al cliente cuando elige transferencia.</p></div>
    </section>

    <section class="<?= CLS_CARD ?> p-6 md:p-8" aria-labelledby="s-ben">
        <h2 id="s-ben" class="<?= $h2 ?>">Tres beneficios del inicio</h2>
        <p class="text-sm text-slate-500 mb-6">Los mensajes que aparecen debajo de las ofertas.</p>
        <div class="grid gap-6 md:grid-cols-3">
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="space-y-3">
                    <?= $campo("beneficio{$i}_titulo", "Título $i", 'maxlength="50"') ?>
                    <?= $campo("beneficio{$i}_texto", "Texto $i", 'maxlength="120"') ?>
                </div>
            <?php endfor; ?>
        </div>
    </section>

    <button class="<?= CLS_BTN ?>">Guardar ajustes</button>
</form>

<form method="post" class="<?= CLS_CARD ?> p-6 md:p-8 mt-10" aria-labelledby="s-cuenta" novalidate>
    <?= csrf_campo() ?><input type="hidden" name="accion" value="cuenta">
    <h2 id="s-cuenta" class="<?= $h2 ?>">Mi cuenta</h2>
    <p class="text-sm text-slate-500 mb-6">Con este correo y contraseña entras al panel. Para cambiar algo necesitas tu contraseña actual.</p>
    <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="<?= CLS_LABEL ?>" for="nombre">Nombre</label><input class="<?= $cl('nombre') ?>" id="nombre" name="nombre" value="<?= e($cuenta['nombre']) ?>" maxlength="120" required><?= $err('nombre') ?></div>
        <div><label class="<?= CLS_LABEL ?>" for="email">Correo</label><input class="<?= $cl('email') ?>" id="email" type="email" name="email" value="<?= e($cuenta['email']) ?>" maxlength="160" required autocomplete="username"><?= $err('email') ?></div>
        <div><label class="<?= CLS_LABEL ?>" for="clave_actual">Contraseña actual</label><input class="<?= $cl('clave_actual') ?>" id="clave_actual" type="password" name="clave_actual" required autocomplete="current-password"><?= $err('clave_actual') ?></div>
        <div><label class="<?= CLS_LABEL ?>" for="clave_nueva">Nueva contraseña (opcional)</label><input class="<?= $cl('clave_nueva') ?>" id="clave_nueva" type="password" name="clave_nueva" minlength="8" autocomplete="new-password"><?= $err('clave_nueva') ?><p class="<?= CLS_AYUDA ?>">Mínimo 8 caracteres. Déjala vacía para conservar la actual.</p></div>
    </div>
    <button class="<?= CLS_BTN ?> mt-6">Guardar mi cuenta</button>
</form>
