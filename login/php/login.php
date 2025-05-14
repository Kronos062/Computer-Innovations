<?php
include '../../conexion/conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_SESSION['usuario_nombre'])) {
        echo json_encode(["logueado" => true, "nombre" => $_SESSION['usuario_nombre']]);
    } else {
        echo json_encode(["logueado" => false]);
    }
    exit;
}

$correo = $_POST['email'];
$contraseña = $_POST['password'];

if (!isset($correo) || !isset($contraseña)) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan datos"]);
        //A falta de probar:
    header('Location: https://computerinnovations.com/web/login/html/login.html#fd');
    exit;
}

$sql = "SELECT id_usuario, nombreApellido, contrasena FROM Usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id_usuario, $nombre, $hash);
    $stmt->fetch();
    
    if (password_verify($contraseña, $hash)) {
        //Selecciona el cliente i emplead basandose en el usuario
        $sql = "SELECT e.id_empleado, c.id_cliente FROM Usuarios u LEFT JOIN Empleados e ON u.id_usuario = e.id_usuario LEFT JOIN Clientes c ON u.id_usuario = c.id_usuario WHERE u.id_usuario = ?";
        $newstmt = $conexion->prepare($sql);
        $newstmt->bind_param("i", $id_usuario);
        $newstmt->execute();
        $newstmt->store_result();
        $newstmt->bind_result($id_empleado, $id_cliente);
        $newstmt->fetch();

        if ($newstmt->num_rows > 0) {
            if ($id_cliente != null) {
                $_SESSION['cliente_id'] = $id_cliente;
            } elseif ($id_empleado != null) {
                $_SESSION['empleado_id'] = $id_empleado;
            }
        }
        $newstmt->close();

        setcookie('usuario_nombre', $nombre, time() + 3600, '/');
        setcookie('id_usuario', $id_usuario, time() + 3600, '/');
        $_SESSION['usuario_id'] = $id_usuario;
        $_SESSION['usuario_nombre'] = $nombre;
        echo json_encode(["mensaje" => "Has iniciado sesión correctamente"]);
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        //A falta de probar:
        header('Location: https://computerinnovations.com/web/main/html/main.html');
    } else {
        http_response_code(403);
        echo "$contraseña  " . " $hash";
        echo json_encode(["error" => "Contraseña incorrecta"]);
        //A falta de probar:
        header('Location: https://computerinnovations.com/web/login/html/login.html#pi');
    }
} else {
    //implementar
}

$stmt->close();
$conexion->close();
?>