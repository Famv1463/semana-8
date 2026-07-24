CREATE DATABASE TIENDA
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE TIENDA;

CREATE TABLE PRODUCTO(
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL
);

CREATE TABLE CLIENTE(
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    direccion VARCHAR(200)
);

CREATE TABLE COMPRA (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    total DECIMAL(10 , 2 ) NOT NULL,
    fecha DATE NOT NULL,
    id_producto INT,
    id_cliente INT,
    FOREIGN KEY (id_producto)
        REFERENCES PRODUCTO (id_producto),
    FOREIGN KEY (id_cliente)
        REFERENCES CLIENTE (id_cliente)
);