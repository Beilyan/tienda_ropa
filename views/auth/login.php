<?php /* Login. Recibe: $volver, $error, $email, $mensajes. */ ?>
            <h2 class="text-4xl font-semibold">¡Hola de nuevo!</h2>
            <p class="text-slate-500 mt-4 mb-10">Ingresa a tu cuenta para continuar.</p>

            <?php foreach ($mensajes as $m): ?><p class="mb-6 border border-blue-200 bg-blue-50 text-blue-800 rounded-2xl px-5 py-4"><?= e($m['mensaje']) ?></p><?php endforeach; ?>
            <?php if ($error): ?><p role="alert" class="mb-6 border border-rose-200 bg-rose-50 text-rose-800 rounded-2xl px-5 py-4"><?= e($error) ?></p><?php endif; ?>

            <form method="post" class="space-y-6">
                <?= csrf_campo() ?>
                <input type="hidden" name="volver" value="<?= e($volver) ?>">
                <div>
                    <label class="block text-sm font-medium mb-3" for="email">Correo electrónico</label>
                    <input class="<?= CLS_CAMPO_AUTH ?>" id="email" type="email" name="email" value="<?= e($email) ?>" placeholder="nombre@correo.com" autocomplete="email" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-3" for="clave">Contraseña</label>
                    <input class="<?= CLS_CAMPO_AUTH ?>" id="clave" type="password" name="clave" placeholder="Tu contraseña" autocomplete="current-password" required>
                </div>
                <button type="submit" class="w-full bg-marca text-white p-5 rounded-xl font-semibold hover:opacity-90 transition duration-300 shadow-lg shadow-marca/20">Iniciar sesión →</button>
            </form>

            <p class="text-center text-slate-500 mt-10">
                ¿No tienes cuenta?
                <a href="<?= e(url('registro.php?volver=' . urlencode($volver))) ?>" class="text-marca font-semibold hover:underline">Crear cuenta</a>
            </p>
            <p class="text-center mt-4"><a href="<?= e(url('principal.php')) ?>" class="text-sm text-slate-400 hover:text-marca">← Volver a la tienda</a></p>
