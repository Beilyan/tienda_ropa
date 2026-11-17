<?php
declare(strict_types=1);

/** Base de los controladores. */
abstract class Controller
{
    /** Muestra una vista dentro de su layout, añadiendo los datos que el layout necesita. */
    protected function vista(string $vista, array $datos = [], ?string $layout = 'tienda'): void
    {
        View::renderizar($vista, $datos + $this->datosLayout($layout, $datos), $layout);
    }

    /** Datos comunes de cada layout (así las vistas no consultan la base de datos). */
    private function datosLayout(?string $layout, array $datos): array
    {
        return match ($layout) {
            'tienda' => [
                'u'         => Auth::usuario(),
                'unidades'  => Carrito::unidades(),
                'busqueda'  => (string) ($_GET['q'] ?? ''),
                'catsMenu'  => Categoria::activas(),
                'mensajes'  => Sesion::leerFlashes(),
                'tituloDoc' => ($datos['titulo'] ?? '') !== ''
                    ? $datos['titulo'] . ' | ' . aj('nombre_tienda')
                    : aj('nombre_tienda') . ' | ' . aj('eslogan'),
            ],
            'admin' => [
                'pendientes' => Pedido::contarPendientes(),
                'mensajes'   => Sesion::leerFlashes(),
            ],
            default => [],
        };
    }

    protected function redirigir(string $ruta): never
    {
        redirigir($ruta);
    }

    protected function esPost(): bool
    {
        return es_post();
    }

    protected function flash(string $tipo, string $mensaje): void
    {
        Sesion::flash($tipo, $mensaje);
    }

    protected function requerirLogin(): array
    {
        return Auth::requerirLogin();
    }

    /** Texto de $_GET/$_POST recortado. */
    protected function texto(array $origen, string $clave, string $defecto = ''): string
    {
        return trim((string) ($origen[$clave] ?? $defecto));
    }
}
