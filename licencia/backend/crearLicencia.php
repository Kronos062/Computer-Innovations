<?php
require_once '../../config/db.php';

function generarCodigoLicencia($longitud = 16) {
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}

$codigo = generarCodigoLicencia();
$id_cliente = $_POST['id_cliente'] ?? null;

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO Licencias (codigo, id_cliente) VALUES (?, ?)");
$stmt->bind_param("si", $codigo, $id_cliente);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "codigo" => $codigo]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
