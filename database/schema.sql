-- =====================================================
-- ESQUEMA DE LA BASE DE DATOS (MySQL / MariaDB de AMPPS)
-- Lo ejecuta instalar.php. Es seguro volver a ejecutarlo:
-- usa CREATE TABLE IF NOT EXISTS y no borra datos.
-- =====================================================

CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  telefono VARCHAR(30) NULL,
  rol ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categorias (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  icono VARCHAR(20) NOT NULL DEFAULT 'camisa',
  orden INT NOT NULL DEFAULT 0,
  activa TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS productos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT UNSIGNED NULL,
  nombre VARCHAR(160) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  descripcion TEXT NULL,
  precio DECIMAL(10,2) NOT NULL,
  precio_oferta DECIMAL(10,2) NULL,
  oferta_hasta DATE NULL,
  color_nombre VARCHAR(40) NULL,
  color_hex CHAR(7) NOT NULL DEFAULT '#258cf0',
  imagen VARCHAR(255) NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  destacado TINYINT(1) NOT NULL DEFAULT 0,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_activo (activo),
  CONSTRAINT fk_prod_cat FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS producto_tallas (
  producto_id INT UNSIGNED NOT NULL,
  talla VARCHAR(10) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  PRIMARY KEY (producto_id, talla),
  CONSTRAINT fk_talla_prod FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS banners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  etiqueta VARCHAR(80) NULL,
  titulo VARCHAR(120) NOT NULL,
  titulo_cursiva VARCHAR(120) NULL,
  texto VARCHAR(255) NULL,
  boton_texto VARCHAR(40) NULL,
  boton_enlace VARCHAR(255) NULL,
  color_fondo CHAR(7) NOT NULL DEFAULT '#258cf0',
  imagen VARCHAR(255) NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  orden INT NOT NULL DEFAULT 0,
  inicio DATE NULL,
  fin DATE NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ajustes (
  clave VARCHAR(60) NOT NULL PRIMARY KEY,
  valor TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cupones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(30) NOT NULL UNIQUE,
  tipo ENUM('porcentaje','monto') NOT NULL DEFAULT 'porcentaje',
  valor DECIMAL(10,2) NOT NULL,
  minimo_compra DECIMAL(10,2) NOT NULL DEFAULT 0,
  usos_max INT NULL,
  usos INT NOT NULL DEFAULT 0,
  inicio DATE NULL,
  fin DATE NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pedidos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(24) NOT NULL UNIQUE,
  usuario_id INT UNSIGNED NULL,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL,
  telefono VARCHAR(30) NOT NULL,
  direccion VARCHAR(255) NOT NULL,
  ciudad VARCHAR(80) NOT NULL,
  cp VARCHAR(12) NOT NULL,
  notas VARCHAR(500) NULL,
  metodo_pago ENUM('contra_entrega','transferencia','whatsapp') NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  descuento DECIMAL(10,2) NOT NULL DEFAULT 0,
  envio DECIMAL(10,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL,
  cupon_codigo VARCHAR(30) NULL,
  estado ENUM('pendiente','confirmado','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_estado (estado),
  CONSTRAINT fk_ped_usr FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pedido_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NULL,
  nombre VARCHAR(160) NOT NULL,
  talla VARCHAR(10) NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  cantidad INT NOT NULL,
  CONSTRAINT fk_item_ped FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
  CONSTRAINT fk_item_prod FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS favoritos (
  usuario_id INT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (usuario_id, producto_id),
  CONSTRAINT fk_fav_usr FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_fav_prod FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
