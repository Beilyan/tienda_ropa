<?php
declare(strict_types=1);

/** DETALLE DE PRODUCTO: imagen, precio vigente, tallas con stock real. */
final class ProductoController extends Controller
{
    public function ver(): void
    {
        $p = Producto::obtener((int) ($_GET['id'] ?? 0));
        if (!$p) {
            http_response_code(404);
            $this->vista('tienda/producto_no_encontrado', ['titulo' => 'Prenda no encontrada']);
            return;
        }

        $id = (int) $p['id'];
        $this->vista('tienda/producto', [
            'titulo'       => $p['nombre'],
            'descripcion'  => mb_substr((string) $p['descripcion'], 0, 150),
            'p'            => $p,
            'id'           => $id,
            'tallas'       => Producto::tallas($id),
            'agotado'      => (int) $p['stock_total'] <= 0,
            'desc'         => Producto::porcentajeDescuento($p),
            'esFav'        => in_array($id, Favorito::idsDelUsuario(), true),
            'relacionados' => Producto::relacionados($p),
        ]);
    }
}
