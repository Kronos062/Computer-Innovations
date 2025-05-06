<?php
require 'db.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el parámetro id']);
    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM Tickets WHERE id_ticket = $id";

if ($mysqli->query($sql)) {
    echo json_encode(['status' => 'eliminado']);
} else {
    http_response_code(500);
    echo json_encode(['error' => $mysqli->error]);
}
?>