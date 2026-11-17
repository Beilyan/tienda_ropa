<?php
declare(strict_types=1);

/** PANEL: resumen del negocio y avisos para mantener todo al día. */
final class AdminPanelController extends AdminController
{
    public function index(): void
    {
        $pendientes = Pedido::contarPendientes();
        $mes = Resumen::ventasDelMes();
        $ventasMes = $mes['ventas'];
        $pedidosMes = $mes['pedidos'];
        $activos = Resumen::productosActivos();
        $agotados = Resumen::agotados();
        $stockBajo = Resumen::stockBajo();
        $ofertasPorVencer = Resumen::ofertasPorVencer();
        $ofertasVencidas = Resumen::ofertasVencidas();
        $bannersVigentes = Resumen::bannersVigentes();
        $bannersPorVencer = Resumen::bannersPorVencer();
        $sinFoto = Resumen::sinFoto();
        $ultimos = Resumen::ultimosPedidos(6);

        // Avisos: cosas que el dueño debería revisar para no perder ventas por datos viejos
        $avisos = [];
        if ($pendientes > 0) $avisos[] = ['rojo', "Tienes $pendientes " . ($pendientes === 1 ? 'pedido pendiente' : 'pedidos pendientes') . ' por confirmar.', 'pedidos.php?estado=pendiente', 'Ver pedidos'];
        if ($agotados) $avisos[] = ['rojo', count($agotados) . ' ' . (count($agotados) === 1 ? 'prenda está agotada' : 'prendas están agotadas') . ' y se muestran como "Agotado": ' . implode(', ', array_slice(array_column($agotados, 'nombre'), 0, 3)) . (count($agotados) > 3 ? '…' : '') . '.', 'productos.php?estado=agotados', 'Actualizar stock'];
        if ($stockBajo) $avisos[] = ['ambar', count($stockBajo) . ' ' . (count($stockBajo) === 1 ? 'prenda tiene' : 'prendas tienen') . ' tallas con 3 piezas o menos.', 'productos.php', 'Revisar stock'];
        if ($ofertasPorVencer) $avisos[] = ['ambar', 'Estas ofertas terminan esta semana: ' . implode(', ', array_map(fn($o) => $o['nombre'] . ' (' . fecha_corta($o['oferta_hasta']) . ')', array_slice($ofertasPorVencer, 0, 3))) . '.', 'productos.php?estado=ofertas', 'Ver ofertas'];
        if ($ofertasVencidas) $avisos[] = ['gris', count($ofertasVencidas) . ' ' . (count($ofertasVencidas) === 1 ? 'oferta ya venció' : 'ofertas ya vencieron') . ': el precio normal se está mostrando solo. Puedes limpiar el dato o extender la fecha.', 'productos.php?estado=vencidas', 'Revisar'];
        if ($bannersVigentes === 0) $avisos[] = ['ambar', 'No hay ningún banner vigente en el inicio; se muestra uno genérico.', 'promociones.php', 'Crear banner'];
        if ($bannersPorVencer > 0) $avisos[] = ['ambar', "$bannersPorVencer " . ($bannersPorVencer === 1 ? 'banner termina' : 'banners terminan') . ' esta semana.', 'promociones.php', 'Ver banners'];
        if ($sinFoto > 0) $avisos[] = ['azul', "$sinFoto " . ($sinFoto === 1 ? 'prenda usa' : 'prendas usan') . ' una ilustración en lugar de foto. Con una foto real se vende más.', 'productos.php', 'Subir fotos'];
        $tonos = ['rojo' => 'border-rose-200 bg-rose-50', 'ambar' => 'border-amber-200 bg-amber-50', 'azul' => 'border-blue-200 bg-blue-50', 'gris' => 'border-slate-200 bg-white'];

        $this->panel('index', compact('avisos', 'tonos', 'pendientes', 'ventasMes', 'pedidosMes', 'activos', 'agotados', 'ultimos'),
            'Panel', 'panel', 'Resumen de ' . aj('nombre_tienda'));
    }
}
