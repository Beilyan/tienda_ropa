<?php
declare(strict_types=1);

/** CATÁLOGO: búsqueda, categorías, talla, precio, ofertas y orden. */
final class CatalogoController extends Controller
{
    public function index(): void
    {
        $porPagina = (int) Config::get('app.por_pagina.catalogo');
        $f = [
            'q'         => trim((string) ($_GET['q'] ?? '')),
            'categoria' => (string) ($_GET['categoria'] ?? ''),
            'talla'     => (string) ($_GET['talla'] ?? ''),
            'min'       => (string) ($_GET['min'] ?? ''),
            'max'       => (string) ($_GET['max'] ?? ''),
            'oferta'    => !empty($_GET['oferta']),
            'orden'     => (string) ($_GET['orden'] ?? 'nuevos'),
        ];
        $categorias = Categoria::activas();
        $total = Producto::contar($f);
        $paginas = max(1, (int) ceil($total / $porPagina));
        $pagina = min(max(1, (int) ($_GET['pagina'] ?? 1)), $paginas);
        $productos = Producto::buscar($f + ['limite' => $porPagina, 'desde' => ($pagina - 1) * $porPagina]);

        $catActual = null;
        foreach ($categorias as $c) {
            if ($c['slug'] === $f['categoria']) {
                $catActual = $c;
            }
        }
        $titulo = $f['oferta'] ? 'Ofertas' : ($catActual['nombre'] ?? ($f['q'] !== '' ? 'Resultados' : 'Catálogo'));
        $hayFiltros = $f['q'] !== '' || $f['categoria'] !== '' || $f['talla'] !== '' || $f['min'] !== '' || $f['max'] !== '' || $f['oferta'];

        $this->vista('tienda/catalogo', compact('titulo', 'f', 'categorias', 'total', 'paginas', 'pagina', 'productos', 'hayFiltros'));
    }
}
