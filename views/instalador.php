<?php /* Instalador (solo nombre de tienda y cuenta del dueño; la base de datos se conecta sola). Recibe: $instalada, $exito, $error, $v. */
$campo = 'w-full p-4 border border-slate-300 rounded-xl outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalar la tienda</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
</head>
<body class="bg-white text-[#202c38]">
<div class="grid grid-cols-1 lg:grid-cols-2 min-h-screen">
    <div class="bg-[#258cf0] p-10 md:p-16 text-white flex flex-col">
        <p class="text-2xl font-bold tracking-[0.25em]">◇ INSTALACIÓN</p>
        <div class="mt-16 lg:mt-24">
            <p class="text-sm font-bold tracking-widest mb-5">UN SOLO PASO</p>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">Deja lista tu tienda<span class="block italic font-serif">en un minuto.</span></h1>
            <p class="mt-6 text-blue-100 text-lg max-w-md">Después de esto, tú mismo cambiarás precios, ofertas, fotos y banners desde el panel, sin depender de nadie.</p>
        </div>
    </div>

    <div class="bg-slate-50 flex justify-center items-center p-6 md:p-14">
        <div class="w-full max-w-lg">
        <?php if ($instalada): ?>
            <h2 class="text-3xl font-semibold">La tienda ya está instalada</h2>
            <p class="text-slate-500 mt-4 mb-8">Por seguridad, el instalador no se puede ejecutar de nuevo. Si necesitas ayuda, consulta a quien instaló tu tienda.</p>
            <a href="<?= e(url('login.php')) ?>" class="inline-block bg-[#258cf0] text-white font-semibold px-7 py-4 rounded-full">Iniciar sesión</a>
        <?php elseif ($exito): ?>
            <h2 class="text-3xl font-semibold">¡Listo! Tu tienda está instalada</h2>
            <p class="text-slate-500 mt-4 mb-8">Inicia sesión con el correo y la contraseña que acabas de elegir: al ser la cuenta del dueño, te llevará directo a tu panel de control.</p>
            <div class="flex flex-wrap gap-3">
                <a href="<?= e(url('login.php')) ?>" class="bg-[#258cf0] text-white font-semibold px-7 py-4 rounded-full">Iniciar sesión</a>
                <a href="<?= e(url('principal.php')) ?>" class="border border-slate-300 font-semibold px-7 py-4 rounded-full hover:bg-white">Ver la tienda</a>
            </div>
        <?php else: ?>
            <h2 class="text-3xl font-semibold">Configurar la tienda</h2>
            <p class="text-slate-500 mt-3 mb-8">Solo necesitamos el nombre de tu tienda y los datos de tu cuenta de dueño. Lo demás se configura solo.</p>

            <?php if ($error): ?><p role="alert" class="mb-6 border border-rose-200 bg-rose-50 text-rose-800 rounded-2xl px-5 py-4"><?= e($error) ?></p><?php endif; ?>

            <form method="post" class="space-y-5" autocomplete="off">
                <?= csrf_campo() ?>
                <fieldset class="space-y-4">
                    <legend class="text-xs font-bold tracking-[0.25em] text-[#258cf0] mb-3">TU TIENDA Y TU CUENTA DE DUEÑO</legend>
                    <div><label class="block text-sm font-medium mb-2" for="nombre_tienda">Nombre de la tienda</label>
                        <input class="<?= $campo ?>" id="nombre_tienda" name="nombre_tienda" value="<?= e($v['nombre_tienda']) ?>" required maxlength="40"></div>
                    <div><label class="block text-sm font-medium mb-2" for="admin_nombre">Tu nombre</label>
                        <input class="<?= $campo ?>" id="admin_nombre" name="admin_nombre" value="<?= e($v['admin_nombre']) ?>" required maxlength="80"></div>
                    <div><label class="block text-sm font-medium mb-2" for="admin_email">Tu correo (con él entrarás como dueño)</label>
                        <input class="<?= $campo ?>" id="admin_email" type="email" name="admin_email" value="<?= e($v['admin_email']) ?>" required></div>
                    <div><label class="block text-sm font-medium mb-2" for="admin_clave">Contraseña (mínimo 8 caracteres)</label>
                        <input class="<?= $campo ?>" id="admin_clave" type="password" name="admin_clave" required minlength="8" autocomplete="new-password"></div>
                </fieldset>

                <label class="flex items-start gap-3 text-sm text-slate-600 border-t border-slate-200 pt-5">
                    <input type="checkbox" name="demo" value="1" class="w-5 h-5 mt-0.5 accent-blue-500" <?= $v['demo'] === '1' ? 'checked' : '' ?>>
                    <span>Cargar prendas, banners y un cupón de ejemplo para ver la tienda funcionando. Luego puedes editarlos o borrarlos desde el panel.</span>
                </label>

                <button type="submit" class="w-full bg-[#258cf0] text-white p-4 rounded-xl font-semibold hover:bg-blue-600 transition">Instalar la tienda →</button>
            </form>
        <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
