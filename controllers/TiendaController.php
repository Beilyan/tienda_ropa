<?php
declare(strict_types=1);

/**
 * PÁGINA PRINCIPAL
 * Todo lo que se ve aquí lo edita el dueño desde el panel:
 * banners, ofertas, productos, categorías y textos.
 */
final class TiendaController extends Controller
{
    public function inicio(): void
    {
        // Banners vigentes (respetan fechas de inicio y fin)
        $banners = Banner::vigentes();
        if (!$banners) { // Respaldo para que el inicio nunca quede vacío
            $banners = [[
                'etiqueta' => 'BIENVENIDO', 'titulo' => aj('eslogan'), 'titulo_cursiva' => '', 'texto' => '',
                'boton_texto' => 'Ver catálogo', 'boton_enlace' => 'lista_ropa.php', 'color_fondo' => color_valido(aj('color_marca')), 'imagen' => null,
            ]];
        }

        $this->vista('tienda/inicio', [
            'titulo'       => '',
            'descripcion'  => aj('eslogan'),
            'banners'      => $banners,
            'ofertas'      => Producto::buscar(['oferta' => 1, 'limite' => 4, 'orden' => 'recientes']),
            'novedades'    => Producto::buscar(['orden' => 'recientes', 'limite' => 8]),
            'categorias'   => Categoria::activas(),
            'totalPrendas' => Producto::contar(),
        ]);
    }
}
