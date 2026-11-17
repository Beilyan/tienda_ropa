<?php /* Cabecera común de la tienda. Recibe: $tituloDoc, $descripcion. */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($tituloDoc) ?></title>
    <meta name="description" content="<?= e($descripcion ?? aj('eslogan')) ?>">
    <?= tailwind_cdn() ?>
</head>
<body class="bg-fondo text-tinta min-h-screen flex flex-col antialiased">
    <a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:bg-tinta focus:text-white focus:px-4 focus:py-2 focus:rounded-full">Saltar al contenido</a>
