<?php
include 'conexion.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

if (isset($datos['nombre']) && isset($datos['correo']) && isset($datos['contraseña'])) {
    $nombre = $datos['nombre'];
    $correo = $datos['correo'];
    $contraseña = password_hash($datos['contraseña'], PASSWORD_DEFAULT);

    $sql_check = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo json_encode(["error" => "El correo ya está asociado a una cuenta"]);
    } else {
        $sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $nombre, $correo, $contraseña);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "¡Te has registrado!"]);
        } else {
            echo json_encode(["error" => "Error al registrar usuario"]);
        }
        $stmt->close();
    }

    $stmt_check->close();
} else {
    echo json_encode(["error" => "Datos incompletos"]);
}

$conexion->close();
?>