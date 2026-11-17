<?php
/* =====================================================
   FUNCIONES REUTILIZABLES (texto, fechas, URL, formularios)
   Son cortas a propósito: se usan desde vistas y controladores.
   ===================================================== */
declare(strict_types=1);

/** Escapa texto para HTML (úsalo SIEMPRE al imprimir datos). */
function e(mixed $texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Construye una URL interna: url('lista_ropa.php?q=camisa') */
function url(string $ruta = ''): string
{
    return BASE . '/' . ltrim($ruta, '/');
}

/** URL de un archivo de /uploads. */
function url_imagen(?string $ruta): string
{
    return $ruta ? url($ruta) : '';
}

function redirigir(string $ruta): never
{
    header('Location: ' . (preg_match('#^https?://#', $ruta) ? $ruta : url($ruta)));
    exit;
}

function es_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
}

function pagina_error(string $mensaje, int $codigo = 500): never
{
    http_response_code($codigo);
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<title>Aviso</title><script src="https://cdn.tailwindcss.com/3.4.17"></script></head>'
        . '<body class="bg-[#f6f8fa] text-[#202c38] min-h-screen flex items-center justify-center p-6">'
        . '<div class="max-w-md bg-white rounded-[30px] p-10 border border-slate-200 text-center">'
        . '<p class="text-xs font-bold tracking-[0.25em] text-[#258cf0] mb-4">AVISO</p>'
        . '<p class="text-lg leading-relaxed">' . e($mensaje) . '</p>'
        . '<a href="javascript:history.back()" class="inline-block mt-8 bg-[#258cf0] text-white font-semibold px-6 py-3 rounded-full">Volver</a>'
        . '</div></body></html>';
    exit;
}

/** Precio sin decimales si es entero: $890 o $890.50 */
function dinero(float|int|string|null $valor): string
{
    $v = (float) $valor;
    return '$' . number_format($v, fmod($v, 1.0) == 0.0 ? 0 : 2);
}

function hoy(): string
{
    return date('Y-m-d');
}

/** "30 nov 2026" */
function fecha_corta(?string $fecha): string
{
    if (!$fecha) {
        return '';
    }
    $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    $t = strtotime($fecha);
    return $t ? date('j', $t) . ' ' . $meses[(int) date('n', $t) - 1] . ' ' . date('Y', $t) : '';
}

/** Valida un color #RRGGBB; si no es válido devuelve el de respaldo. */
function color_valido(?string $hex, string $respaldo = '#258cf0'): string
{
    return $hex !== null && preg_match('/^#[0-9a-fA-F]{6}$/', $hex) ? strtolower($hex) : $respaldo;
}

/** Texto -> slug para URLs: "Camisa Básica" => "camisa-basica" */
function slugify(string $texto): string
{
    $t = mb_strtolower(trim($texto));
    $t = strtr($t, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
    $t = trim((string) preg_replace('/[^a-z0-9]+/', '-', $t), '-');
    return $t !== '' ? $t : 'item';
}

/** Quita todo lo que no sea número (para enlaces de WhatsApp o teléfono). */
function solo_digitos(string $t): string
{
    return (string) preg_replace('/\D+/', '', $t);
}

/** Valida una fecha AAAA-MM-DD (o vacío => null). */
function fecha_o_null(?string $f): ?string
{
    $f = trim((string) $f);
    if ($f === '') {
        return null;
    }
    $d = DateTime::createFromFormat('Y-m-d', $f);
    return $d && $d->format('Y-m-d') === $f ? $f : null;
}

/** Convierte "1,490.50" o "1490" en float; null si no es número. */
function numero_o_null(mixed $v): ?float
{
    $v = trim(str_replace(['$', ',', ' '], '', (string) $v));
    return $v !== '' && is_numeric($v) ? round((float) $v, 2) : null;
}

/** Enlace permitido en banners/avisos: página interna .php o https://… */
function enlace_valido(string $enlace): bool
{
    return (bool) (preg_match('#^(https?://|/)#i', $enlace) || preg_match('/^[a-z0-9_\-\/]+\.php(\?[^\s]*)?$/i', $enlace));
}

/** Mantiene los filtros actuales de la URL y cambia solo lo indicado. */
function url_con(array $cambios, ?string $pagina = null): string
{
    $q = array_merge($_GET, $cambios);
    $q = array_filter($q, fn($v) => $v !== '' && $v !== null && $v !== '0' && $v !== 0 && $v !== false);
    $base = $pagina ?? basename(RUTA_ACTUAL);
    return $base . ($q ? '?' . http_build_query($q) : '');
}

/* ---- Atajos para las vistas ---- */
function flash(string $tipo, string $mensaje): void { Sesion::flash($tipo, $mensaje); }
function csrf_campo(): string { return '<input type="hidden" name="_csrf" value="' . e(Sesion::csrfToken()) . '">'; }
function aj(string $clave): string { return Ajuste::obtener($clave); }
function usuario_actual(): ?array { return Auth::usuario(); }
function es_admin(): bool { return Auth::esAdmin(); }
function parcial(string $nombre, array $datos = []): string { return View::parcial('partials/' . $nombre, $datos); }
