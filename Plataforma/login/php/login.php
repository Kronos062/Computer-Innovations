<?php
include 'conexion.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_SESSION['usuario_nombre'])) {
        echo json_encode(["logueado" => true, "nombre" => $_SESSION['usuario_nombre']]);
    } else {
        echo json_encode(["logueado" => false]);
    }
    exit;
}

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

if (!isset($datos['correo']) || !isset($datos['contraseña'])) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan datos"]);
    exit;
}

$correo = $datos['correo'];
$contraseña = $datos['contraseña'];

$sql = "SELECT id_usuario, nombreApellido, contrasena FROM Usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $nombre, $hash);
    $stmt->fetch();

    if (password_verify($contraseña, $hash)) {
        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario_nombre'] = $nombre;

        echo json_encode(["mensaje" => "Has iniciado sesión correctamente"]);
    } else {
        http_response_code(403);
        echo json_encode(["error" => "Contraseña incorrecta"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "El usuario no existe"]);
}

$stmt->close();
$conexion->close();
?>