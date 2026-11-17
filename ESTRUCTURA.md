# Tienda de ropa (PHP + MySQL + Tailwind)

Tienda en línea con panel para que el dueño cambie precios, ofertas, banners, catálogo, cupones, pedidos y ajustes sin un programador.

## Cómo ponerla a funcionar (AMPPS)
1. Copia la carpeta en `www/tienda_ropa` y enciende Apache (con `mod_rewrite`) y MySQL.
2. Abre `http://localhost/tienda_ropa/`. La primera vez la tienda se prepara sola (no hay pantalla de instalación): se conecta a MySQL con los datos por defecto de AMPPS, crea la base `tienda_ropa`, las tablas, la cuenta del dueño y prendas de ejemplo, y escribe `config/local.php`.
3. Inicia sesión en `http://localhost/tienda_ropa/login.php` con la cuenta del dueño (por defecto `dueno@tienda.com` / `Dueno12345`; cámbiala en Panel > Ajustes > Tu cuenta). Te lleva directo a tu panel (`/admin/`). Los clientes entran por el mismo formulario y van a la tienda.

Los datos por defecto (puerto/contraseña de MySQL, cuenta inicial del dueño, si cargar prendas de ejemplo) están en `config/app.php`. Para empezar de cero, borra la base de datos y el archivo `config/local.php`.

## Estructura
```
config/        app.php (constantes y valores base) · database.php (datos de MySQL) · local.php (se crea sola la primera vez)
core/          Router, Database (única conexión PDO), Sesion, Auth, View, Controller, Modelo, arranque
routes/web.php Todas las direcciones → controlador y método (con su nivel de acceso)
controllers/   Un controlador por tema (Catalogo, Producto, Carrito, Pedido, Favorito, Auth, Perfil)
controllers/admin/  Panel del dueño (Panel, Producto, Categoria, Promocion, Cupon, Pedido, Ajuste, Auth)
models/        Todo el SQL (consultas preparadas): Producto, Categoria, Pedido, Cupon, Banner, Carrito, Usuario…
views/         HTML: layouts/ (header, navbar, footer, tienda, auth, admin), partials/, tienda/, cuenta/, auth/, admin/
helpers/       functions.php (e(), url(), dinero()…), ui.php (piezas visuales), imagenes.php (subida segura)
public/        index.php (punto de entrada), assets/ (tienda.js, admin.js), uploads/ (fotos del dueño)
database/      schema.sql
```
Las direcciones siguen siendo las de siempre (`principal.php`, `lista_ropa.php`, `admin/productos.php`…), por lo que los enlaces guardados y los de los banners no cambian.

## Cómo agregar algo nuevo
- Nueva página: método en un controlador + una línea en `routes/web.php` + una vista en `views/`.
- Nueva consulta: método estático en el modelo que corresponda (nunca SQL en controladores ni vistas).

## Seguridad incluida
Consultas preparadas · `e()` en toda salida · token CSRF en todos los POST (lo comprueba el Router) · contraseñas con `password_hash` · límite de intentos de login · rutas `login`/`admin` protegidas · fotos validadas y recodificadas con GD · carpetas internas cerradas por `.htaccess`.
