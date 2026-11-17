<?php
/* Crear cuenta. Recibe: $volver, $v, $errores. */
$c = fn(string $k) => CLS_CAMPO_AUTH . (isset($errores[$k]) ? ' !border-rose-400' : '');
$err = fn(string $k) => isset($errores[$k]) ? '<p class="text-sm text-rose-600 mt-1.5" role="alert">' . e($errores[$k]) . '</p>' : '';
?>
            <h2 class="text-4xl font-semibold">Crear cuenta</h2>
            <p class="text-slate-500 mt-4 mb-8">Solo te tomará un minuto.</p>
            <form method="post" class="space-y-5" novalidate>
                <?= csrf_campo() ?>
                <input type="hidden" name="volver" value="<?= e($volver) ?>">
                <div><label class="block text-sm font-medium mb-2" for="nombre">Nombre completo</label>
                    <input class="<?= $c('nombre') ?>" id="nombre" name="nombre" value="<?= e($v['nombre']) ?>" autocomplete="name" required><?= $err('nombre') ?></div>
                <div><label class="block text-sm font-medium mb-2" for="email">Correo electrónico</label>
                    <input class="<?= $c('email') ?>" id="email" type="email" name="email" value="<?= e($v['email']) ?>" autocomplete="email" required><?= $err('email') ?></div>
                <div><label class="block text-sm font-medium mb-2" for="telefono">Teléfono (opcional)</label>
                    <input class="<?= $c('telefono') ?>" id="telefono" type="tel" name="telefono" value="<?= e($v['telefono']) ?>" autocomplete="tel"><?= $err('telefono') ?></div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium mb-2" for="clave">Contraseña</label>
                        <input class="<?= $c('clave') ?>" id="clave" type="password" name="clave" autocomplete="new-password" minlength="8" required><?= $err('clave') ?></div>
                    <div><label class="block text-sm font-medium mb-2" for="clave2">Repite la contraseña</label>
                        <input class="<?= $c('clave2') ?>" id="clave2" type="password" name="clave2" autocomplete="new-password" required><?= $err('clave2') ?></div>
                </div>
                <button type="submit" class="w-full bg-marca text-white p-5 rounded-xl font-semibold hover:opacity-90 transition shadow-lg shadow-marca/20">Crear mi cuenta →</button>
            </form>
            <p class="text-center text-slate-500 mt-8">¿Ya tienes cuenta? <a href="<?= e(url('login.php?volver=' . urlencode($volver))) ?>" class="text-marca font-semibold hover:underline">Inicia sesión</a></p>
