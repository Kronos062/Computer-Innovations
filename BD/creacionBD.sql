create DATABASE IF NOT EXISTS CI_NA_Tickets;

use CI_NA_Tickets;

CREATE USER IF NOT EXISTS 'adminCINA'@'localhost' IDENTIFIED BY 'adminCINAadmin';
CREATE USER IF NOT EXISTS 'empleadoCINA'@'localhost' IDENTIFIED BY 'empleadoCINA';
CREATE USER IF NOT EXISTS 'clientCINA'@'localhost' IDENTIFIED BY '1234';

GRANT ALL PRIVILEGES ON CI_NA_Tickets.* TO 'adminCINA'@'localhost';

create table IF NOT EXISTS Categoria(
    id_categoria INT AUTO_INCREMENT NOT NULL UNIQUE,
    categoria VARCHAR(100),
    PRIMARY KEY (id_categoria)
);


CREATE TABLE IF NOT EXISTS Usuarios (
    id_usuario INT AUTO_INCREMENT NOT NULL UNIQUE,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    nombreApellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_usuario)
);

CREATE TABLE IF NOT EXISTS Empleados (
    id_empleado INT AUTO_INCREMENT UNIQUE NOT NULL,
    id_usuario INT NOT NULL UNIQUE,
    id_categoria INT NOT NULL ,
    PRIMARY KEY (id_empleado),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Clientes (
    id_cliente INT AUTO_INCREMENT UNIQUE NOT NULL,
    id_usuario INT NOT NULL UNIQUE,
    PRIMARY KEY (id_cliente),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Tickets (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    id_cliente INT NOT NULL,
    id_categoria INT NOT NULL,
    asunto VARCHAR(200) NOT NULL,
    descripcion VARCHAR(5000),
    direccion VARCHAR(150),
    solucion VARCHAR(5000),
    prioridad VARCHAR(15) NOT NULL DEFAULT 1,
    estado VARCHAR(25) NOT NULL DEFAULT 'Abierto',
    visitado BOOLEAN NOT NULL DEFAULT false,
    fechaEmision DATE NOT NULL DEFAULT (CURRENT_DATE),
    fechaCierre DATE NULL,
    FOREIGN KEY (id_empleado) REFERENCES Empleados(id_empleado) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Licencias (
    id_licencia INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(16) UNIQUE NOT NULL,
    id_cliente INT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente) ON DELETE SET NULL
);

GRANT SELECT, INSERT, DROP, UPDATE ON CI_NA_Tickets.Tickets TO 'empleadoCINA'@'localhost';
GRANT SELECT, INSERT, DROP, UPDATE ON CI_NA_Tickets.Clientes TO 'empleadoCINA'@'localhost';
GRANT SELECT, INSERT, DROP, UPDATE ON CI_NA_Tickets.Categoria TO 'empleadoCINA'@'localhost';
GRANT SELECT, INSERT, DROP, UPDATE ON CI_NA_Tickets.Tickets TO 'clientCINA'@'localhost';

-- INSERTS ESENCIALES
INSERT INTO Categoria(categoria) VALUES ('Front End'), ('Back End');
INSERT INTO Usuarios(usuario, email, nombreApellido, contrasena) VALUES
('adminFrontEnd', 'adminFrontEnd@ComputerInnovations.com', 'adminFrontEnd', '1234qwerty'),
('adminBackEnd', 'adminBackEnd@ComputerInnovations.com', 'adminBackEnd','qwerty1234'),
('testUser', 'test@ComputerInnovations.com', 'testUser','1234');
INSERT INTO Empleados(id_usuario, id_categoria) VALUES (1, 1), (2, 2);
INSERT INTO Clientes(id_usuario) VALUES (3);

CREATE INDEX idx_empleados_id_categoria 
ON Empleados (id_categoria);
CREATE INDEX idx_tickets_id_empleado 
ON Tickets (id_empleado);
CREATE INDEX idx_tickets_id_categoria 
ON Tickets (id_categoria);
CREATE INDEX idx_tickets_id_cliente 
ON Tickets (id_cliente);
