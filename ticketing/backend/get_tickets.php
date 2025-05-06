<?php
header('Content-Type: application/json');
require 'db.php';

$sql = "SELECT id_ticket AS id, asunto AS titulo, direccion AS lugar, prioridad, 
        (SELECT nombreApellido FROM Usuarios u 
         JOIN Clientes c ON u.id_usuario = c.id_usuario 
         WHERE c.id_cliente = t.id_cliente LIMIT 1) AS afectado 
        FROM Tickets t 
        WHERE estado = 'Abierto'
        ORDER BY fechaEmision DESC";

$result = $mysqli->query($sql);

$tickets = [];

while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}

echo json_encode($tickets);
?>
