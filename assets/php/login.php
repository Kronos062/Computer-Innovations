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

if (isset($datos['correo']) && isset($datos['contraseña'])) {
    $correo = $datos['correo'];
    $contraseña = $datos['contraseña'];

    $sql = "SELECT id, nombre, contraseña FROM usuarios WHERE correo = ?";
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
            
            echo json_encode(["mensaje" => "Has sesiado la inición"]);
        } else {
            echo json_encode(["error" => "La contraseña es incorrecta"]);
        }
    } else {
        echo json_encode(["error" => "El usuario no existe"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Aquí faltan datos..."]);
}

$conexion->close();
?>