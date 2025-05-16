<?php
include '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['username'] ?? '');
    $correo = trim($_POST['email'] ?? '');
    $contrasenaSinHash = $_POST['password'] ?? '';
    $confirmarContrasena = $_POST['confirm-password'] ?? ''; 
    if ($contrasenaSinHash !== $confirmarContrasena) {
        //A falta de testear:
        header('Location: ../html/registro.html#pnc');
        die;
    }
    $contrasena = password_hash($contrasenaSinHash, PASSWORD_DEFAULT);

    if ($nombre === '' || $correo === '' || $contrasena === '' || $confirmarContrasena === '') {
        //A falta de testear:
        header('Location: ../html/registro.html#fi');
        die;
    }

    $sql_check = "SELECT id_usuario FROM Usuarios WHERE email = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        $conexion->close();
        //A falta de testear:
        header('Location: ../html/registro.html#ca');
        die;
    } else {
        $stmt_check->close();
    }

    $sql = "INSERT INTO Usuarios (usuario, nombreApellido, email, contrasena) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $usuario = explode("@", $correo)[0];
    $stmt->bind_param("ssss", $usuario, $nombre, $correo, $contrasena);

    if ($stmt->execute()) {
        //A falta de testear:
        header('Location: ../../login/html/login.html');
        die;
    } else {
        //A falta de testear:
        header('Location: ../html/registro.html#ei');
        die;
    }

    $stmt->close();
    $conexion->close();
}
?>