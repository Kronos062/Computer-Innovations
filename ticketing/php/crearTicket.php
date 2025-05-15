<?php
session_start();

//Imports
include '../../conexion/conexion.php';

$id_cliente = $_SESSION['cliente_id']; // = el id de la sesion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ( $id_cliente != null) {
        //Declarar variables
        $IDcategoria = $_POST["IDcategoria"];
        $asunto = $_POST["asunto"];
        $descripcion = $_POST["descripcion"];
        $direccion = $_POST["direccion"];
        $prioridad = $_POST["prioridad"];

        // Busca si existen tickets previos de esa categoria
        $query = "SELECT COUNT(id_ticket) from CI_NA_Tickets.Tickets WHERE id_categoria = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $IDcategoria);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($numTickets);
        $stmt->fetch();
        $stmt->close();

        if ($numTickets > 0) {
            // Busca para cada empleado cuantos tickets tiene asignados, elije el que menos tiene
            $query = "SELECT e.id_empleado
                FROM Empleados e
                LEFT JOIN (
                    SELECT id_empleado,
                        COUNT(*) AS cantidad_tickets
                    FROM Tickets
                    GROUP BY id_empleado
                ) t ON e.id_empleado = t.id_empleado
                WHERE e.id_categoria = ?
                ORDER BY IFNULL(t.cantidad_tickets,0) ASC
                LIMIT 1";

            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $IDcategoria);
            $stmt->execute();
            $stmt->bind_result($id_empleado);
            $stmt->fetch();
            $stmt->close();
        } else {
            // Genera el id_empleado más antiguo en esa categoria por default 
            $query = "SELECT id_empleado FROM CI_NA_Tickets.Empleados WHERE id_categoria = ? ORDER BY id_empleado ASC LIMIT 1";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $IDcategoria);
            $stmt->execute();
            $stmt->bind_result($id_empleado);
            $stmt->fetch();
            $stmt->close();
        }

        try {
            //Insert del ticket
            $query = "INSERT INTO CI_NA_Tickets.Tickets (id_empleado, id_cliente, id_categoria, asunto, descripcion, direccion, prioridad) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("iiisssi", $id_empleado, $id_cliente, $IDcategoria, $asunto, $descripcion, $direccion, $prioridad);
            $stmt->execute();
            $stmt->store_result();
        } catch (Eception) {
            echo "<h2>Te faltan datos por rellenar</h2>";
            header("Location: ../html/crearTicket.html#di");
        }

        if ($stmt->affected_rows > 0) {
            header("Location: ../../tickets/php/tickets.php");
        } else {
            header("Location: ../html/crearTicket.html");
            echo json_encode(["error" => "Error al crear el ticket: " . $stmt->error]);
        }
        $stmt->close();
        $conexion->close();
    } else {
        echo "<h2>Para crear un ticket debes iniciar sesión como cliente</h2>";
        echo "<a href='../../login/html/login.html'>Iniciar Sesion</a>";
    }
} else {
    echo json_encode(["error" => "Método GET en lugar de POST"]);
}