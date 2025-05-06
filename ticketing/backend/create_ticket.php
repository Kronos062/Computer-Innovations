<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

// Simulación de IDs (debes obtenerlos de sesión o frontend si implementas login)
$id_empleado = 1; // ejemplo fijo
$id_cliente = 1;  // ejemplo fijo
$id_categoria = 1; // ejemplo fijo

$titulo = $mysqli->real_escape_string($data['titulo']);
$descripcion = $mysqli->real_escape_string($data['descripcion']);
$lugar = $mysqli->real_escape_string($data['lugar']);
$prioridad = $mysqli->real_escape_string($data['prioridad']);

$sql = "INSERT INTO Tickets (id_empleado, id_cliente, id_categoria, asunto, descripcion, direccion, prioridad) 
        VALUES ($id_empleado, $id_cliente, $id_categoria, '$titulo', '$descripcion', '$lugar', '$prioridad')";

if ($mysqli->query($sql)) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    echo json_encode(['error' => $mysqli->error]);
}
?>
