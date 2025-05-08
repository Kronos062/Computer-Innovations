<?php
include 'conexion.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Sesión no iniciada"]);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT titulo, descripcion, lugar, afectado, prioridad FROM Tickets WHERE id_usuario = ? AND estado = 'abierto'";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$tickets = [];
while ($fila = $resultado->fetch_assoc()) {
    $tickets[] = $fila;
}

echo json_encode($tickets);

$stmt->close();
$conexion->close();
?>