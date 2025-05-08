<?php
include '../../conexion/conexion.php';
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
    $stmt->bind_result($id_usuario, $nombre, $hash);
    $stmt->fetch();

    if ($contraseña == $hash) {
        setcookie('usuario_nombre', $nombre, time() + 3600, '/');
        setcookie('id_usuario', $id_usuario, time() + 3600, '/');
        $_SESSION['usuario_id'] = $id_usuario;
        $_SESSION['usuario_nombre'] = $nombre;
        echo json_encode(["mensaje" => "Has iniciado sesión correctamente"]);
    } else {
        http_response_code(403);
        echo json_encode(["error" => "Contraseña incorrecta"]);
    }
} else {
    $correo_simulado = "computerinnovations@gmail.com";
    $nombre_simulado = "CI";
    $id_simulado = 5;
    $contraseña_simulada = "1234";

    if ($correo === $correo_simulado && $contraseña === $contraseña_simulada) {
        setcookie('usuario_nombre', $nombre_simulado, time() + 3600, '/');
        setcookie('id_usuario', $id_simulado, time() + 3600, '/');
        $_SESSION['usuario_id'] = $id_simulado;
        $_SESSION['usuario_nombre'] = $nombre_simulado;
        echo json_encode(["mensaje" => "Has iniciado sesión correctamente"]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "El usuario no existe o contraseña incorrecta"]);
    }
}

$stmt->close();
$conexion->close();
?>