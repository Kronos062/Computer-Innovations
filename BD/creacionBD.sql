create DATABASE IF NOT EXISTS CI_NA_Tickets;

use CI_NA_Tickets;

CREATE USER 'admin'@'localhost' IDENTIFIED BY 'PerroSanxeDimision';
CREATE USER 'client'@'localhost' IDENTIFIED BY '1234';

GRANT ALL PRIVILEGES ON CI_NA_Tickets.Tickets TO 'admin'@'localhost';

create table IF NOT EXISTS Categoria(
    id_categoria INT AUTO_INCREMENT NOT NULL UNIQUE,
    categoria VARCHAR(100),
    PRIMARY KEY (id_categoria)
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
    PRIMARY KEY (id_usuario),
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

CREATE TABLE IF NOT EXISTS Tickets (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NULL,
    id_cliente INT NOT NULL,
    id_categoria INT NOT NULL,
    asunto VARCHAR(200) NOT NULL,
    descripcion VARCHAR(5000) NOT NULL,
    solucion VARCHAR(5000),
    estado VARCHAR(25) NOT NULL DEFAULT 'Abierto',
    fechaEmision DATE NOT NULL DEFAULT (CURRENT_DATE),
    fechaCierre DATE NULL,
    FOREIGN KEY (id_empleado) REFERENCES Empleados(id_empleado) ON DELETE SET NULL,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE
);