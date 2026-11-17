<?php /* Cupones. Recibe: $cupones, $c, $v, $errores, $mostrarForm. */
$estadoCupon = function (array $x): array {
    if (!(int) $x['activo']) return ['Pausado', 'gris'];
    if ($x['inicio'] && $x['inicio'] > hoy()) return ['Programado', 'azul'];
    if ($x['fin'] && $x['fin'] < hoy()) return ['Vencido', 'gris'];
    if ($x['usos_max'] !== null && (int) $x['usos'] >= (int) $x['usos_max']) return ['Agotado', 'ambar'];
    return ['Activo', 'verde'];
};
$cl = fn(string $k) => CLS_INPUT . (isset($errores[$k]) ? ' !border-rose-400' : '');
$err = fn(string $k) => isset($errores[$k]) ? '<p class="text-sm text-rose-600 mt-1.5" role="alert">' . e($errores[$k]) . '</p>' : '';
?>
<?php if ($mostrarForm): ?>
<section class="<?= CLS_CARD ?> p-6 md:p-8 mb-10" aria-labelledby="t-cupon">
    <h2 id="t-cupon" class="text-xl font-semibold mb-6"><?= $c ? 'Editar cupón' : 'Nuevo cupón' ?></h2>
    <form method="post" class="grid gap-5 sm:grid-cols-2" novalidate>
        <?= csrf_campo() ?><input type="hidden" name="accion" value="guardar"><input type="hidden" name="id" value="<?= (int) ($c['id'] ?? 0) ?>">
        <div><label class="<?= CLS_LABEL ?>" for="codigo">Código que escribe el cliente</label>
            <input class="<?= $cl('codigo') ?> uppercase" id="codigo" name="codigo" value="<?= e($v['codigo']) ?>" maxlength="30" required placeholder="BUENFIN20"><?= $err('codigo') ?></div>
        <div><label class="<?= CLS_LABEL ?>" for="tipo">Tipo de descuento</label>
            <select class="<?= CLS_INPUT ?>" id="tipo" name="tipo"><option value="porcentaje" <?= $v['tipo'] === 'porcentaje' ? 'selected' : '' ?>>Porcentaje (%)</option><option value="monto" <?= $v['tipo'] === 'monto' ? 'selected' : '' ?>>Monto fijo ($)</option></select></div>
        <div><label class="<?= CLS_LABEL ?>" for="valor">Valor del descuento</label>
            <input class="<?= $cl('valor') ?>" id="valor" name="valor" inputmode="decimal" value="<?= e((string) $v['valor']) ?>" required placeholder="Ej. 15"><?= $err('valor') ?></div>
        <div><label class="<?= CLS_LABEL ?>" for="minimo_compra">Compra mínima ($)</label>
            <input class="<?= $cl('minimo_compra') ?>" id="minimo_compra" name="minimo_compra" inputmode="decimal" value="<?= e((string) $v['minimo_compra']) ?>"><?= $err('minimo_compra') ?></div>
        <div><label class="<?= CLS_LABEL ?>" for="usos_max">Límite de usos (opcional)</label>
            <input class="<?= $cl('usos_max') ?>" id="usos_max" name="usos_max" inputmode="numeric" value="<?= e((string) $v['usos_max']) ?>" placeholder="Sin límite"><?= $err('usos_max') ?></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="<?= CLS_LABEL ?>" for="inicio">Desde</label><input class="<?= $cl('inicio') ?>" id="inicio" type="date" name="inicio" value="<?= e((string) $v['inicio']) ?>"><?= $err('inicio') ?></div>
            <div><label class="<?= CLS_LABEL ?>" for="fin">Hasta</label><input class="<?= $cl('fin') ?>" id="fin" type="date" name="fin" value="<?= e((string) $v['fin']) ?>"><?= $err('fin') ?></div>
        </div>
        <label class="sm:col-span-2 flex items-center gap-3"><input type="checkbox" name="activo" value="1" class="w-5 h-5 accent-blue-500" <?= $v['activo'] ? 'checked' : '' ?>> <strong>Activo</strong></label>
        <div class="sm:col-span-2 flex flex-wrap gap-3">
            <button class="<?= CLS_BTN ?>"><?= $c ? 'Guardar cupón' : 'Crear cupón' ?></button>
            <a href="<?= e(url('admin/cupones.php')) ?>" class="<?= CLS_BTN_2 ?>">Cancelar</a>
        </div>
    </form>
</section>
<?php endif; ?>

<?php if ($cupones): ?>
<div class="<?= CLS_CARD ?> divide-y divide-slate-100">
    <?php foreach ($cupones as $x): [$txt, $tono] = $estadoCupon($x); $id = (int) $x['id']; ?>
        <div class="p-4 md:p-5 flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-52">
                <p class="font-semibold tracking-wide"><?= e($x['codigo']) ?> <?= chip($txt, $tono) ?></p>
                <p class="text-sm text-slate-500 mt-1">
                    <?= $x['tipo'] === 'porcentaje' ? e(rtrim(rtrim(number_format((float) $x['valor'], 2), '0'), '.')) . '% de descuento' : e(dinero($x['valor'])) . ' de descuento' ?>
                    <?= (float) $x['minimo_compra'] > 0 ? ' · desde ' . e(dinero($x['minimo_compra'])) : '' ?>
                    · usado <?= (int) $x['usos'] ?><?= $x['usos_max'] !== null ? '/' . (int) $x['usos_max'] : '' ?> <?= (int) $x['usos'] === 1 ? 'vez' : 'veces' ?>
                    <?= $x['fin'] ? ' · hasta ' . e(fecha_corta($x['fin'])) : '' ?>
                </p>
            </div>
            <div class="flex items-center gap-4">
                <a href="<?= e(url('admin/cupones.php?editar=' . $id)) ?>" class="<?= CLS_BTN_2 ?> !py-2 !px-5 text-sm">Editar</a>
                <form method="post"><?= csrf_campo() ?><input type="hidden" name="accion" value="alternar"><input type="hidden" name="id" value="<?= $id ?>"><button class="text-sm text-slate-500 hover:text-marca underline underline-offset-4"><?= (int) $x['activo'] ? 'Pausar' : 'Activar' ?></button></form>
                <form method="post" data-confirmar="¿Eliminar el cupón <?= e($x['codigo']) ?>?"><?= csrf_campo() ?><input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= $id ?>"><button class="text-sm text-slate-400 hover:text-rose-600 underline underline-offset-4">Eliminar</button></form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <p class="<?= CLS_CARD ?> p-8 text-center text-slate-500">No hay cupones. Crea uno para impulsar tus ventas.</p>
<?php endif; ?>
