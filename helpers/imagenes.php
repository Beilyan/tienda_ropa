<?php
/* =====================================================
   SUBIDA SEGURA DE IMÁGENES
   ===================================================== */
declare(strict_types=1);

/**
 * Guarda una imagen subida y devuelve su ruta relativa (uploads/productos/xxx.jpg).
 * Valida el tipo real del archivo, limita el tamaño y la vuelve a codificar con GD
 * (así se elimina cualquier código oculto dentro de la imagen).
 * Lanza RuntimeException con un mensaje en español si algo falla.
 */
function subir_imagen(array $archivo, string $carpeta = 'productos'): string
{
    $errores = [
        UPLOAD_ERR_INI_SIZE => 'La imagen es demasiado pesada para el servidor.',
        UPLOAD_ERR_FORM_SIZE => 'La imagen es demasiado pesada.',
        UPLOAD_ERR_PARTIAL => 'La imagen se subió incompleta. Inténtalo de nuevo.',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal del servidor.',
        UPLOAD_ERR_CANT_WRITE => 'No se pudo guardar la imagen en el servidor.',
    ];
    if (!in_array($carpeta, ['productos', 'banners', 'logo'], true)) {
        throw new RuntimeException('Carpeta de imágenes no válida.');
    }
    if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException($errores[$archivo['error'] ?? 0] ?? 'No se pudo subir la imagen.');
    }
    if ($archivo['size'] > Config::get('app.subidas.max_bytes')) {
        throw new RuntimeException('La imagen pesa más de 5 MB. Usa una más ligera.');
    }
    if (!is_uploaded_file($archivo['tmp_name'])) {
        throw new RuntimeException('Archivo no válido.');
    }
    $info = @getimagesize($archivo['tmp_name']);
    $tipos = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if (!$info || !isset($tipos[$info[2]])) {
        throw new RuntimeException('Solo se aceptan imágenes JPG, PNG o WEBP.');
    }
    $ext = $tipos[$info[2]];
    $dir = RAIZ . '/public/uploads/' . $carpeta;
    if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
        throw new RuntimeException('No se pudo crear la carpeta de imágenes.');
    }
    $nombre = bin2hex(random_bytes(10)) . '.' . $ext;
    $destino = $dir . '/' . $nombre;

    if (function_exists('imagecreatetruecolor')) {
        $origen = match ($ext) {
            'jpg'  => @imagecreatefromjpeg($archivo['tmp_name']),
            'png'  => @imagecreatefrompng($archivo['tmp_name']),
            default => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($archivo['tmp_name']) : false,
        };
        if ($origen) {
            [$an, $al] = [imagesx($origen), imagesy($origen)];
            $max = (int) Config::get('app.subidas.max_lado');
            if (max($an, $al) > $max) {
                $f = $max / max($an, $al);
                $nuevo = imagecreatetruecolor((int) round($an * $f), (int) round($al * $f));
                imagealphablending($nuevo, false);
                imagesavealpha($nuevo, true);
                imagecopyresampled($nuevo, $origen, 0, 0, 0, 0, imagesx($nuevo), imagesy($nuevo), $an, $al);
                $origen = $nuevo;
            }
            if ($ext !== 'jpg') {
                imagealphablending($origen, false); // conserva la transparencia de logos y banners en PNG/WEBP
                imagesavealpha($origen, true);
            }
            $ok = match ($ext) {
                'jpg'  => imagejpeg($origen, $destino, 85),
                'png'  => imagepng($origen, $destino, 6),
                default => imagewebp($origen, $destino, 85),
            };
            if ($ok) {
                return 'uploads/' . $carpeta . '/' . $nombre;
            }
        }
    }
    // Sin GD: se guarda tal cual (ya se comprobó que es una imagen real)
    if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
        throw new RuntimeException('No se pudo guardar la imagen.');
    }
    return 'uploads/' . $carpeta . '/' . $nombre;
}

/** Borra una imagen subida (solo dentro de public/uploads). */
function borrar_imagen(?string $ruta): void
{
    if ($ruta && str_starts_with($ruta, 'uploads/') && !str_contains($ruta, '..')) {
        $f = RAIZ . '/public/' . $ruta;
        if (is_file($f)) {
            @unlink($f);
        }
    }
}
