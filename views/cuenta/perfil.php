<?php
/* Mi perfil. Recibe: $u, $v, $errores, $pedidos (cantidad), $favs (cantidad). */
$c = fn(string $k) => 'w-full rounded-xl border bg-white px-4 py-3.5 outline-none focus:border-marca focus:ring-2 focus:ring-marca/20 ' . (isset($errores[$k]) ? 'border-rose-400' : 'border-slate-200');
$err = fn(string $k) => isset($errores[$k]) ? '<p class="text-sm text-rose-600 mt-1.5" role="alert">' . e($errores[$k]) . '</p>' : '';
?>
<div class="mb-8">
    <p class="text-marca text-xs font-bold tracking-[0.25em] mb-4">MI CUENTA</p>
    <h1 class="text-3xl md:text-5xl font-semibold tracking-tight">Hola, <?= e(strtok($u['nombre'], ' ')) ?></h1>
</div>

<div class="grid gap-8 lg:grid-cols-[1fr_340px] items-start">
    <div class="space-y-8">
        <section class="bg-white border border-slate-200 rounded-[30px] p-6 md:p-8">
            <h2 class="text-xl font-semibold mb-6">Mis datos</h2>
            <form method="post" class="grid gap-5 sm:grid-cols-2" novalidate>
                <?= csrf_campo() ?><input type="hidden" name="accion" value="datos">
                <div class="sm:col-span-2"><label class="block text-sm font-medium mb-2" for="nombre">Nombre completo</label>
                    <input class="<?= $c('nombre') ?>" id="nombre" name="nombre" value="<?= e($v['nombre']) ?>" required><?= $err('nombre') ?></div>
                <div><label class="block text-sm font-medium mb-2" for="email">Correo electrónico</label>
                    <input class="<?= $c('email') ?>" id="email" type="email" name="email" value="<?= e($v['email']) ?>" required><?= $err('email') ?></div>
                <div><label class="block text-sm font-medium mb-2" for="telefono">Teléfono</label>
                    <input class="<?= $c('telefono') ?>" id="telefono" type="tel" name="telefono" value="<?= e($v['telefono']) ?>"><?= $err('telefono') ?></div>
                <div class="sm:col-span-2"><button class="bg-marca text-white font-semibold px-8 py-3.5 rounded-full hover:opacity-90">Guardar cambios</button></div>
            </form>
        </section>

        <section class="bg-white border border-slate-200 rounded-[30px] p-6 md:p-8">
            <h2 class="text-xl font-semibold mb-6">Cambiar contraseña</h2>
            <form method="post" class="grid gap-5 sm:grid-cols-2" novalidate>
                <?= csrf_campo() ?><input type="hidden" name="accion" value="clave">
                <div class="sm:col-span-2"><label class="block text-sm font-medium mb-2" for="actual">Contraseña actual</label>
                    <input class="<?= $c('actual') ?>" id="actual" type="password" name="actual" autocomplete="current-password" required><?= $err('actual') ?></div>
                <div><label class="block text-sm font-medium mb-2" for="nueva">Nueva contraseña</label>
                    <input class="<?= $c('nueva') ?>" id="nueva" type="password" name="nueva" autocomplete="new-password" minlength="8" required><?= $err('nueva') ?></div>
                <div><label class="block text-sm font-medium mb-2" for="nueva2">Repite la nueva contraseña</label>
                    <input class="<?= $c('nueva2') ?>" id="nueva2" type="password" name="nueva2" autocomplete="new-password" required><?= $err('nueva2') ?></div>
                <div class="sm:col-span-2"><button class="border border-slate-300 font-semibold px-8 py-3.5 rounded-full hover:border-marca hover:text-marca">Actualizar contraseña</button></div>
            </form>
        </section>
    </div>

    <aside class="space-y-4">
        <a href="<?= e(url('mis_pedidos.php')) ?>" class="block bg-white border border-slate-200 rounded-[20px] p-6 hover:border-marca">
            <p class="text-3xl font-bold text-marca"><?= $pedidos ?></p><p class="text-slate-600">Mis pedidos →</p></a>
        <a href="<?= e(url('lista_favoritos.php')) ?>" class="block bg-white border border-slate-200 rounded-[20px] p-6 hover:border-marca">
            <p class="text-3xl font-bold text-marca"><?= $favs ?></p><p class="text-slate-600">Prendas favoritas →</p></a>
        <?php if ($u['rol'] === 'admin'): ?>
            <a href="<?= e(url('admin/')) ?>" class="block bg-marca text-white rounded-[20px] p-6 font-semibold hover:opacity-90">Ir al panel del dueño →</a>
        <?php endif; ?>
    </aside>
</div>
