<?php
/* =====================================================
   RUTAS
   Cada línea une una dirección con el método de un controlador.
   Las direcciones conservan los nombres de página de siempre (principal.php, admin/productos.php…)
   para que los enlaces guardados y los de los banners sigan funcionando.
   Acceso: 'publico' (cualquiera) · 'login' (cliente con sesión) · 'admin' (solo el dueño).
   Todos los envíos POST llevan token CSRF, que el Router comprueba.
   ===================================================== */
declare(strict_types=1);

return (new Router())
    // Tienda
    ->ruta('principal.php',      [TiendaController::class,    'inicio'])
    ->ruta('lista_ropa.php',     [CatalogoController::class,  'index'])
    ->ruta('producto.php',       [ProductoController::class,  'ver'])
    ->ruta('carrito.php',        [CarritoController::class,   'manejar'])
    ->ruta('checkout.php',       [PedidoController::class,    'checkout'])
    ->ruta('gracias.php',        [PedidoController::class,    'gracias'])
    ->ruta('favorito.php',       [FavoritoController::class,  'alternar'])
    ->ruta('lista_favoritos.php', [FavoritoController::class, 'index'], 'login')

    // Cuenta del cliente
    ->ruta('login.php',          [AuthController::class,      'login'])
    ->ruta('registro.php',       [AuthController::class,      'registro'])
    ->ruta('logout.php',         [AuthController::class,      'logout'])
    ->ruta('perfil.php',         [PerfilController::class,    'index'], 'login')
    ->ruta('mis_pedidos.php',    [PedidoController::class,    'misPedidos'], 'login')

    // Instalador (se bloquea solo cuando ya hay un dueño)
    ->ruta('instalar.php',       [InstaladorController::class, 'instalar'])

    // Panel del dueño
    ->ruta('admin/login.php',    [AuthController::class,           'entradaDueno'])   // el dueño entra por login.php
    ->ruta('admin/logout.php',   [AuthController::class,           'logout'])
    ->ruta('admin/index.php',    [AdminPanelController::class,     'index'],    'admin')
    ->ruta('admin/productos.php', [AdminProductoController::class, 'index'],    'admin')
    ->ruta('admin/producto_form.php', [AdminProductoController::class, 'formulario'], 'admin')
    ->ruta('admin/categorias.php', [AdminCategoriaController::class, 'index'],  'admin')
    ->ruta('admin/promociones.php', [AdminPromocionController::class, 'index'], 'admin')
    ->ruta('admin/cupones.php',  [AdminCuponController::class,      'index'],    'admin')
    ->ruta('admin/pedidos.php',  [AdminPedidoController::class,    'index'],    'admin')
    ->ruta('admin/pedido.php',   [AdminPedidoController::class,    'detalle'],  'admin')
    ->ruta('admin/ajustes.php',  [AdminAjusteController::class,    'index'],    'admin');
