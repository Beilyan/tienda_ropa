<?php
/* =====================================================
   PIEZAS VISUALES REUTILIZABLES
   Clases de formulario del panel, logo, Tailwind y la ilustración
   de prenda que se usa cuando un producto no tiene foto.
   ===================================================== */
declare(strict_types=1);

// Clases reutilizables para que todos los formularios del panel se vean igual
const CLS_INPUT = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 outline-none focus:border-marca focus:ring-2 focus:ring-marca/20 disabled:bg-slate-50';
const CLS_LABEL = 'block text-sm font-medium mb-2';
const CLS_AYUDA = 'text-xs text-slate-500 mt-1.5';
const CLS_BTN = 'inline-flex items-center justify-center gap-2 bg-marca text-white font-semibold px-6 py-3 rounded-full hover:opacity-90 transition';
const CLS_BTN_2 = 'inline-flex items-center justify-center gap-2 border border-slate-300 bg-white font-semibold px-6 py-3 rounded-full hover:border-marca hover:text-marca transition';
const CLS_CARD = 'bg-white border border-slate-200 rounded-[20px]';
const CLS_CAMPO_AUTH = 'w-full p-4 border border-slate-300 rounded-xl outline-none focus:border-marca focus:ring-2 focus:ring-marca/20 bg-white';

/** Configuración de Tailwind compartida por tienda y panel. */
function tailwind_cdn(): string
{
    $marca = color_valido(aj('color_marca'));
    return '<script src="https://cdn.tailwindcss.com/3.4.17"></script>' . "\n"
        . '<script>tailwind.config = { theme: { extend: { colors: { marca: ' . json_encode($marca) . ', tinta: "#202c38", fondo: "#f6f8fa" } } } };</script>';
}

/**
 * Logotipo de la tienda. Si el dueño subió una imagen (Panel > Ajustes > Logo) se muestra esa imagen;
 * si no, el cubo con el nombre de la tienda.
 */
function logo_tienda(string $clases = ''): string
{
    $logo = aj('logo');
    if ($logo !== '') {
        return '<span class="flex items-center ' . e($clases) . '">'
            . '<img src="' . e(url_imagen($logo)) . '" alt="' . e(aj('nombre_tienda')) . '" class="h-10 w-auto max-w-[220px] object-contain"></span>';
    }
    return '<span class="flex items-center gap-4 ' . e($clases) . '">'
        . '<svg class="w-8 h-9 text-marca shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 30" aria-hidden="true">'
        . '<path d="M12 1 21 6 12 11 3 6 12 1Z"/><path d="M3 6v17l9 5V11M21 6v17l-9 5"/></svg>'
        . '<span class="text-2xl font-bold tracking-[0.25em] uppercase">' . e(aj('nombre_tienda')) . '</span></span>';
}

/** Etiqueta de color para estados. */
function chip(string $texto, string $tono = 'gris'): string
{
    $t = [
        'verde' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'rojo' => 'bg-rose-50 text-rose-700 border-rose-200',
        'ambar' => 'bg-amber-50 text-amber-700 border-amber-200', 'azul' => 'bg-blue-50 text-blue-700 border-blue-200',
        'gris' => 'bg-slate-100 text-slate-500 border-slate-200', 'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
    ][$tono] ?? 'bg-slate-100 text-slate-500 border-slate-200';
    return '<span class="inline-block border rounded-full px-3 py-1 text-xs font-semibold whitespace-nowrap ' . $t . '">' . e($texto) . '</span>';
}

/** Tarjeta de producto con el estilo de principal.php (vista: partials/tarjeta_producto). */
function tarjeta_producto(array $p, int $numero = 0): string
{
    return parcial('tarjeta_producto', [
        'p' => $p,
        'numero' => $numero,
        'esFav' => in_array((int) $p['id'], Favorito::idsDelUsuario(), true),
    ]);
}

function ilustracion_prenda(string $icono, string $hex, string $clase = 'w-[75%] h-[75%]'): string
{
    $hex = color_valido($hex);
    $trazo = 'rgba(255,255,255,0.65)';
    $formas = [
        'camisa' => ['M65 35 L40 48 L15 85 L40 105 L55 88 L55 170 L145 170 L145 88 L160 105 L185 85 L160 48 L135 35 Q100 75 65 35Z',
                     '<path d="M65 35 Q100 75 135 35" fill="none" stroke="' . $trazo . '" stroke-width="3"/>'],
        'sudadera' => ['M66 32 L38 44 L22 132 L46 138 L57 94 L57 172 L143 172 L143 94 L154 138 L178 132 L162 44 L134 32 Q100 72 66 32Z',
                       '<path d="M66 32 Q100 72 134 32" fill="none" stroke="' . $trazo . '" stroke-width="3"/><path d="M76 138 L124 138 L130 166 L70 166Z" fill="none" stroke="' . $trazo . '" stroke-width="2.5" stroke-linejoin="round"/>'],
        'chaqueta' => ['M66 32 L38 44 L22 132 L46 138 L57 94 L57 172 L143 172 L143 94 L154 138 L178 132 L162 44 L134 32 Q100 72 66 32Z',
                       '<path d="M66 32 L100 78 L134 32" fill="none" stroke="' . $trazo . '" stroke-width="3" stroke-linejoin="round"/><path d="M100 78 L100 172" stroke="' . $trazo . '" stroke-width="3"/>'],
        'vestido' => ['M78 30 L64 30 L60 72 L74 86 L46 172 L154 172 L126 86 L140 72 L136 30 L122 30 Q100 62 78 30Z',
                      '<path d="M74 86 Q100 98 126 86" fill="none" stroke="' . $trazo . '" stroke-width="3"/>'],
        'pantalon' => ['M60 30 L140 30 L148 172 L108 172 L100 82 L92 172 L52 172Z',
                       '<path d="M60 44 L140 44" stroke="' . $trazo . '" stroke-width="3"/><path d="M100 44 L100 82" stroke="' . $trazo . '" stroke-width="2.5"/>'],
    ];
    [$ruta, $detalle] = $formas[$icono] ?? $formas['camisa'];
    return '<svg viewBox="0 0 200 200" class="' . e($clase) . '" aria-hidden="true" focusable="false">'
        . '<path d="' . $ruta . '" fill="' . $hex . '"/>' . $detalle . '</svg>';
}

/** Fondo suave derivado del color de la prenda (mezcla con blanco). */
function tinte(string $hex, float $mezcla = 0.86): string
{
    $hex = ltrim(color_valido($hex), '#');
    $c = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    foreach ($c as &$v) {
        $v = (int) round($v + (255 - $v) * $mezcla);
    }
    return sprintf('#%02x%02x%02x', ...$c);
}

/** Imagen del producto: foto subida o ilustración. Llena su contenedor cuadrado. */
function imagen_producto(array $p, string $claseSvg = 'w-[75%] h-[75%] group-hover:scale-110 transition duration-500'): string
{
    if (!empty($p['imagen'])) {
        return '<img src="' . e(url_imagen($p['imagen'])) . '" alt="' . e($p['nombre']) . '" loading="lazy" '
            . 'class="w-full h-full object-cover group-hover:scale-105 transition duration-500">';
    }
    return ilustracion_prenda((string) ($p['icono'] ?? 'camisa'), (string) $p['color_hex'], $claseSvg);
}

