<?php
/* Layout de las pantallas de acceso. Recibe: $titulo, $etiqueta, $linea1, $linea2, $texto, $contenido. */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> | <?= e(aj('nombre_tienda')) ?></title>
    <?= tailwind_cdn() ?>
</head>
<body class="bg-white text-tinta antialiased">
<div class="grid grid-cols-1 lg:grid-cols-2 min-h-screen">
    <div class="bg-marca p-10 md:p-16 text-white flex flex-col">
        <?php if (aj('logo') !== ''): ?>
        <a href="<?= e(url('principal.php')) ?>" class="self-start bg-white rounded-2xl px-4 py-3" aria-label="<?= e(aj('nombre_tienda')) ?>, ir al inicio"><?= logo_tienda() ?></a>
        <?php else: ?>
        <a href="<?= e(url('principal.php')) ?>" class="text-2xl font-bold tracking-[0.25em] uppercase">◇ <?= e(aj('nombre_tienda')) ?></a>
        <?php endif; ?>
        <div class="mt-14 lg:mt-24">
            <p class="text-sm font-bold tracking-widest mb-5"><?= e($etiqueta) ?></p>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                <?= e($linea1) ?>
                <span class="block italic font-serif"><?= e($linea2) ?></span>
            </h1>
            <p class="mt-6 text-white/80 text-lg max-w-md"><?= e($texto) ?></p>
        </div>
    </div>
    <div class="bg-slate-50 flex justify-center items-center p-6 md:p-16">
        <div class="w-full max-w-lg">
<?= $contenido ?>
        </div>
    </div>
</div>
</body>
</html>
