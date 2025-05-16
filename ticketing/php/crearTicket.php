<?php
session_start();

//Imports
include '../../conexion/conexion.php';

$id_cliente = $_SESSION['id_usuario']; // = el id de la sesion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ( $id_cliente != null) {
        //Declarar variables
        $IDcategoria = $_POST["IDcategoria"];
        $asunto = $_POST["asunto"];
        $descripcion = $_POST["descripcion"];
        $direccion = $_POST["direccion"];
        $prioridad = $_POST["prioridad"];

        try {
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
        } catch (Exception) {
            header('Location: ../html/crearTicket.html#ee');
        }

        try {
            //Insert del ticket
            $query = "INSERT INTO CI_NA_Tickets.Tickets (id_empleado, id_usuario, id_categoria, asunto, descripcion, direccion, prioridad) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("iiisssi", $id_empleado, $id_usuario, $IDcategoria, $asunto, $descripcion, $direccion, $prioridad);
            $stmt->execute();
            $stmt->store_result();
        } catch (Eception) {
            header("Location: ../html/crearTicket.html#di");
        }

        if ($stmt->affected_rows > 0) {
            header("Location: ../../tickets/php/tickets.php");
        } else {
            header("Location: ../html/crearTicket.html#et");
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