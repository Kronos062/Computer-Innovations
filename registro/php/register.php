<?php
include '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['username'] ?? '');
    $correo = trim($_POST['email'] ?? '');
    $contraseñaSinHash = $_POST['password'] ?? '';
    $confirmarContraseña = $_POST['confirm-password'] ?? ''; 
    if ($contraseñaSinHash !== $confirmarContraseña) {
        echo "<script>alert('Las contraseñas no coinciden.');</script>";
        header("Refresh:0");
        //A falta de testear:
        header('Location: https://computerinnovations.com/web/registro/html/registro.html#pnc');
        die;
    }
    $contraseña = password_hash($contraseñaSinHash, PASSWORD_DEFAULT);

    if ($nombre === '' || $correo === '' || $contraseña === '' || $confirmarContraseña === '') {
        echo "<script>alert('Todos los campos son obligatorios.');</script>";
        header("Refresh:0");
        //A falta de testear:
        header('Location: https://computerinnovations.com/web/registro/html/registro.html#fi');
        die;
    }

    $sql_check = "SELECT id_usuario FROM Usuarios WHERE email = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $stmt_check->get_result();

    if ($stmt_check->num_rows > 0) {
        echo "<script>alert('El correo ya está asociado a una cuenta.');</script>";
        $stmt_check->close();
        $conexion->close();
        //A falta de testear:
        header('Location: https://computerinnovations.com/web/registro/html/registro.html#ca');
        die;
    } else {
        $stmt_check->close();
    }

    $sql = "INSERT INTO Usuarios (usuario, nombreApellido, email, contrasena) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $usuario = explode("@", $correo)[0];
    $stmt->bind_param("ssss", $usuario, $nombre, $correo, $contraseña);

    if ($stmt->execute()) {
        echo "<script>alert('¡Te has registrado con éxito!'); window.location.href='../../login/html/login.html';</script>";
        //A falta de testear:
        header('Location: https://computerinnovations.com/web/login/html/login.html');
        die;
    } else {
        echo "<script>alert('Error al registrar usuario.');</script>";
        //A falta de testear:
        header('Location: https://computerinnovations.com/web/registro/html/registro.html#ei');
        die;
    }

    $stmt->close();
    $conexion->close();
}
?>