<?php
/* =====================================================
   CONFIGURACIÓN GENERAL DE LA APLICACIÓN
   Listas fijas (tallas, estados, métodos de pago), valores por defecto
   de los ajustes editables y límites. Nada de credenciales aquí:
   las de la base de datos viven en config/local.php (no se sube a git).
   ===================================================== */

define('TALLAS', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Única']);
define('ESTADOS_PEDIDO', [
    'pendiente'  => 'Pendiente',
    'confirmado' => 'Confirmado',
    'enviado'    => 'Enviado',
    'entregado'  => 'Entregado',
    'cancelado'  => 'Cancelado',
]);
define('METODOS_PAGO', [
    'contra_entrega' => 'Pago contra entrega',
    'transferencia'  => 'Transferencia bancaria',
    'whatsapp'       => 'Coordinar por WhatsApp',
]);
define('ICONOS_CATEGORIA', [
    'camisa' => 'Camisa / playera', 'chaqueta' => 'Chaqueta', 'sudadera' => 'Sudadera',
    'vestido' => 'Vestido', 'pantalon' => 'Pantalón',
]);

return [
    'sesion'        => 'tienda_sid',
    'zona_horaria'  => 'America/Mazatlan',
    // El instalador se conecta solo con estos datos (valores por defecto de AMPPS); el dueño no los ve.
    // Solo un programador los cambia, si su MySQL usa otro puerto o contraseña.
    'base_de_datos' => ['host' => '127.0.0.1', 'puertos' => [3306], 'nombre' => 'tienda_ropa', 'accesos' => [['root', 'mysql'], ['root', '']]],
    'por_pagina'    => ['catalogo' => 12, 'admin_productos' => 20, 'admin_pedidos' => 20],
    'subidas'       => ['max_bytes' => 5 * 1024 * 1024, 'max_lado' => 1400],
    // Valores de respaldo: el dueño los cambia en Panel > Ajustes
    'ajustes_base'  => [
        'nombre_tienda' => 'MODA',
        'logo' => '',
        'eslogan' => 'Tu estilo, sin reglas.',
        'color_marca' => '#258cf0',
        'aviso_activo' => '1',
        'aviso_texto' => 'Envío gratis en compras mayores a $999',
        'aviso_enlace' => '',
        'whatsapp' => '',
        'email_contacto' => '',
        'telefono' => '',
        'direccion' => '',
        'horario' => '',
        'instagram' => '',
        'facebook' => '',
        'costo_envio' => '99',
        'envio_gratis_desde' => '999',
        'pago_contra_entrega' => '1',
        'pago_transferencia' => '1',
        'pago_whatsapp' => '1',
        'datos_transferencia' => '',
        'pie_texto' => 'Todos los derechos reservados.',
        'beneficio1_titulo' => 'Envíos a todo el país',
        'beneficio1_texto' => 'Recibe tu pedido en la puerta de tu casa.',
        'beneficio2_titulo' => 'Cambios fáciles',
        'beneficio2_texto' => 'Si no te queda, lo resolvemos contigo.',
        'beneficio3_titulo' => 'Atención directa',
        'beneficio3_texto' => 'Escríbenos y te ayudamos a elegir.',
    ],
];
