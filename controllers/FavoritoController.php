<?php
declare(strict_types=1);

/** FAVORITOS: guardar o quitar una prenda (responde JSON si lo pide fetch) y lista. */
final class FavoritoController extends Controller
{
    public function alternar(): void
    {
        $esAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';
        if (!$this->esPost()) {
            $this->redirigir('lista_favoritos.php');
        }

        $u = Auth::usuario();
        if (!$u) {
            if ($esAjax) {
                $this->json(['ok' => false, 'login' => true], 401);
            }
            $this->flash('info', 'Inicia sesión para guardar tus favoritos.');
            $this->redirigir('login.php');
        }

        [$existe, $favorito] = Favorito::alternar((int) $u['id'], (int) ($_POST['producto_id'] ?? 0));

        if ($esAjax) {
            $this->json(['ok' => $existe, 'favorito' => $favorito]);
        }
        $volver = $_SERVER['HTTP_REFERER'] ?? '';
        $this->redirigir(str_starts_with($volver, (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '')) ? $volver : 'lista_favoritos.php');
    }

    public function index(): void
    {
        $u = Auth::usuario();
        $this->vista('tienda/favoritos', [
            'titulo'    => 'Mis favoritos',
            'productos' => Producto::buscar(['favoritos_de' => $u['id'], 'orden' => 'recientes']),
        ]);
    }

    private function json(array $datos, int $codigo = 200): never
    {
        http_response_code($codigo);
        header('Content-Type: application/json');
        echo json_encode($datos);
        exit;
    }
}
