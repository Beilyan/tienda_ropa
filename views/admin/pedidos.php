<?php /* Lista de pedidos. Recibe: $pedidos, $conteos, $q, $estado, $total, $pagina, $paginas. */
$tonoEstado = ['pendiente' => 'ambar', 'confirmado' => 'azul', 'enviado' => 'indigo', 'entregado' => 'verde', 'cancelado' => 'gris'];
?>
<form method="get" class="<?= CLS_CARD ?> p-4 mb-6 flex flex-wrap gap-3 items-end" role="search">
    <div class="flex-1 min-w-52"><label class="<?= CLS_LABEL ?>" for="q">Buscar</label><input class="<?= CLS_INPUT ?>" id="q" name="q" value="<?= e($q) ?>" placeholder="Código, nombre, correo o teléfono"></div>
    <div><label class="<?= CLS_LABEL ?>" for="estado">Estado</label>
        <select class="<?= CLS_INPUT ?>" id="estado" name="estado" data-autoenviar-campo>
            <option value="">Todos</option>
            <?php foreach (ESTADOS_PEDIDO as $k => $t): ?><option value="<?= $k ?>" <?= $estado === $k ? 'selected' : '' ?>><?= e($t) ?> (<?= (int) ($conteos[$k] ?? 0) ?>)</option><?php endforeach; ?>
        </select></div>
    <button class="<?= CLS_BTN ?>">Filtrar</button>
    <?php if ($q !== '' || $estado !== ''): ?><a class="<?= CLS_BTN_2 ?>" href="<?= e(url('admin/pedidos.php')) ?>">Limpiar</a><?php endif; ?>
</form>

<?php if ($pedidos): ?>
<div class="<?= CLS_CARD ?> divide-y divide-slate-100">
    <?php foreach ($pedidos as $p): ?>
        <a href="<?= e(url('admin/pedido.php?id=' . $p['id'])) ?>" class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 hover:bg-slate-50">
            <span class="min-w-52">
                <span class="font-semibold"><?= e($p['codigo']) ?></span>
                <span class="block text-sm text-slate-500"><?= e($p['nombre']) ?> · <?= e($p['ciudad']) ?> · <?= e(fecha_corta($p['creado_en'])) ?></span>
            </span>
            <span class="text-sm text-slate-500 hidden md:block"><?= e(METODOS_PAGO[$p['metodo_pago']] ?? $p['metodo_pago']) ?></span>
            <span class="flex items-center gap-4"><?= chip(ESTADOS_PEDIDO[$p['estado']], $tonoEstado[$p['estado']] ?? 'gris') ?><strong class="w-24 text-right"><?= e(dinero($p['total'])) ?></strong></span>
        </a>
    <?php endforeach; ?>
</div>
<?php if ($paginas > 1): ?>
    <nav class="flex items-center justify-center gap-4 mt-8" aria-label="Paginación">
        <?php if ($pagina > 1): ?><a class="<?= CLS_BTN_2 ?>" href="<?= e(url_con(['pagina' => $pagina - 1])) ?>">← Anterior</a><?php endif; ?>
        <span class="text-sm text-slate-500">Página <?= $pagina ?> de <?= $paginas ?></span>
        <?php if ($pagina < $paginas): ?><a class="<?= CLS_BTN_2 ?>" href="<?= e(url_con(['pagina' => $pagina + 1])) ?>">Siguiente →</a><?php endif; ?>
    </nav>
<?php endif; ?>
<?php else: ?>
    <p class="<?= CLS_CARD ?> p-8 text-center text-slate-500"><?= $q !== '' || $estado !== '' ? 'No encontramos pedidos con ese filtro.' : 'Todavía no hay pedidos.' ?></p>
<?php endif; ?>
