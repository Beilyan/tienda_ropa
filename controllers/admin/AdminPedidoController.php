<?php
declare(strict_types=1);

/** PEDIDOS: lista con filtros y detalle con cambio de estado. Cancelar devuelve las piezas al inventario y es definitivo. */
final class AdminPedidoController extends AdminController
{
    private const POR_PAGINA = 20;

    public function index(): void
    {
        $estado = array_key_exists($_GET['estado'] ?? '', ESTADOS_PEDIDO) ? $_GET['estado'] : '';
        $q = $this->texto($_GET, 'q');
        $total = Pedido::contar($estado, $q);
        $paginas = max(1, (int) ceil($total / self::POR_PAGINA));
        $pagina = min(max(1, (int) ($_GET['pagina'] ?? 1)), $paginas);
        $pedidos = Pedido::listar($estado, $q, self::POR_PAGINA, ($pagina - 1) * self::POR_PAGINA);
        $conteos = Pedido::conteosPorEstado();

        $this->panel('pedidos', compact('pedidos', 'conteos', 'q', 'estado', 'total', 'pagina', 'paginas'),
            'Pedidos', 'pedidos', number_format($total) . ($total === 1 ? ' pedido' : ' pedidos'));
    }

    public function detalle(): void
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        $p = Pedido::porId($id);
        if (!$p) {
            $this->flash('error', 'No encontramos ese pedido.');
            $this->redirigir('admin/pedidos.php');
        }

        if ($this->esPost()) {
            $nuevo = (string) ($_POST['estado'] ?? '');
            if (!array_key_exists($nuevo, ESTADOS_PEDIDO)) {
                $this->flash('error', 'Estado no válido.');
            } elseif ($p['estado'] === 'cancelado') {
                $this->flash('error', 'Un pedido cancelado ya no se puede cambiar.');
            } elseif ($nuevo !== $p['estado']) {
                try {
                    Pedido::cambiarEstado($p, $nuevo);
                    $this->flash('ok', $nuevo === 'cancelado' ? 'Pedido cancelado. Las piezas volvieron al inventario.' : 'Pedido marcado como ' . mb_strtolower(ESTADOS_PEDIDO[$nuevo]) . '.');
                } catch (Throwable) {
                    $this->flash('error', 'No pudimos cambiar el estado. Intenta de nuevo.');
                }
            }
            $this->redirigir('admin/pedido.php?id=' . $id);
        }

        $items = Pedido::items($id);
        $tono = ['pendiente' => 'ambar', 'confirmado' => 'azul', 'enviado' => 'indigo', 'entregado' => 'verde', 'cancelado' => 'gris'][$p['estado']] ?? 'gris';
        $tel = solo_digitos((string) $p['telefono']);
        $msg = rawurlencode("Hola {$p['nombre']}, te escribimos de " . aj('nombre_tienda') . " sobre tu pedido {$p['codigo']}.");
        $final = $p['estado'] === 'cancelado';

        $this->panel('pedido', compact('p', 'items', 'id', 'tono', 'tel', 'msg', 'final'),
            'Pedido ' . $p['codigo'], 'pedidos', fecha_corta($p['creado_en']),
            ['href' => 'admin/pedidos.php', 'texto' => '← Pedidos', 'estilo' => 'secundario']);
    }
}
