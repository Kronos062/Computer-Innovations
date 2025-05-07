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

$correo_simulado = "ejemplo@gmail.com";
$nombre_simulado = "cliente";
$id_simulado = 5;
$contraseña_simulada = "1234";
if ($correo === $correo_simulado && $contraseña === $contraseña_simulada) {

    setcookie('usuario_nombre', $nombre_simulado, time() + 3600, '/');
    setcookie('id_usuario', $id_simulado, time() + 3600, '/');
    
    if ($contraseña === $contraseña_simulada) {
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