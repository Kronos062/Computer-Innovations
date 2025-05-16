<?php
session_start();
header("Content-Type: application/json");
$_SESSION = [];
session_destroy();

if (isset($_COOKIE['usuario_nombre'])) {
    setcookie('usuario_nombre', '', time() - 3600, '/', '', false, true);
}
if (isset($_COOKIE['id_usuario'])) {
    setcookie('id_usuario', '', time() - 3600, '/', '', false, true);
}
echo json_encode(["res" => "Has cerrado sesión correctamente"]);
?>
