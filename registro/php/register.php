<?php
include 'conexion.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

if (!isset($datos['nombre']) || !isset($datos['correo']) || !isset($datos['contraseña'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$nombre = trim($datos['nombre']);
$correo = trim($datos['correo']);
$contraseña = password_hash($datos['contraseña'], PASSWORD_DEFAULT);
$id_rol = 2;


$sql_check = "SELECT id_usuario FROM Usuarios WHERE email = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $correo);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "El correo ya está asociado a una cuenta"]);
    $stmt_check->close();
    $conexion->close();
    exit;
}
$stmt_check->close();

$sql = "INSERT INTO Usuarios (usuario, nombreApellido, email, contrasena) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$usuario = explode("@", $correo)[0];
$stmt->bind_param("ssssi", $usuario, $nombre, $correo, $contraseña, $id_rol);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "¡Te has registrado correctamente!"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al registrar usuario"]);
}

$stmt->close();
$conexion->close();
?>
