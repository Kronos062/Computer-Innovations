create DATABASE IF NOT EXISTS CI_NA_Tickets;

use CI_NA_Tickets;

CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin.Armin1928';
CREATE USER 'client'@'localhost' IDENTIFIED BY 'Dabu.ten10-10';

GRANT ALL PRIVILEGES ON CI_NA_Tickets.Tickets TO 'admin'@'localhost';

create table IF NOT EXISTS Categoria(
    id_categoria INT AUTO_INCREMENT NOT NULL UNIQUE,
    categoria VARCHAR(100),
    PRIMARY KEY (id_categoria)
);

create table IF NOT EXISTS Rol(
    id_rol INT AUTO_INCREMENT NOT NULL UNIQUE,
    rol VARCHAR(100)
    PRIMARY KEY (id_rol)
);

create table IF NOT EXISTS Servicios(
    id_servicio INT AUTO_INCREMENT NOT NULL,
    detallesServicio VARCHAR(100),
    costoInicial DECIMAL(8, 2),
    costoMes DECIMAL(8, 2),
    costoAnio DECIMAL(8, 2),
    PRIMARY KEY (id_servicio)
    );

CREATE TABLE IF NOT EXISTS Usuarios (
    id_usuario INT AUTO_INCREMENT NOT NULL UNIQUE,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    nombreApellido VARCHAR(100),
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(100),
    PRIMARY KEY (id_usuario),
    FOREIGN KEY (rol) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Empleados (
    id_empleado INT AUTO_INCREMENT UNIQUE,
    id_usuario INT NOT NULL UNIQUE,
    id_categoria INT NOT NULL ,
    PRIMARY KEY (id_empleado),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Clientes (
    id_cliente INT AUTO_INCREMENT UNIQUE,
    versionServicio INT,
    id_usuario INT NOT NULL UNIQUE,
    PRIMARY KEY (id_cliente),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

create table IF NOT EXISTS Tickets(
    id_ticket INT AUTO_INCREMENT NOT NULL UNIQUE,
    id_empleado INT NOT NULL,
    id_cliente INT NOT NULL,
    id_categoria INT NOT NULL,
    asunto VARCHAR(200),
    descripcion VARCHAR(5000),
    solucion VARCHAR(5000),
    estado VARCHAR(25),
    fechaEmision DATE,
    fechaCierre DATE,
    PRIMARY KEY (id_ticket),
    FOREIGN KEY (id_empleado) REFERENCES Empleados(id_empleado),
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente),
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria)
    );