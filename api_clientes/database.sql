-- Base de datos para práctica API REST + XAMPP
-- Crear en phpMyAdmin o ejecutar desde consola MySQL.

CREATE DATABASE IF NOT EXISTS api_clientes_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE api_clientes_db;

DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS clientes;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    telefono VARCHAR(30) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    producto VARCHAR(120) NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    estado ENUM('pendiente','pagado','enviado','cancelado') NOT NULL DEFAULT 'pendiente',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_clientes
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

INSERT INTO clientes (nombre, correo, telefono) VALUES
('Ana Pérez', 'ana.perez@correo.cl', '+56911111111'),
('Diego Soto', 'diego.soto@correo.cl', '+56922222222'),
('Camila Rojas', 'camila.rojas@correo.cl', '+56933333333');

INSERT INTO pedidos (cliente_id, producto, cantidad, estado) VALUES
(1, 'Notebook', 1, 'pagado'),
(1, 'Mouse inalámbrico', 2, 'pendiente'),
(2, 'Monitor 24 pulgadas', 1, 'enviado');
