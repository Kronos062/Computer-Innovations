<?php
$servidor = "localhost";
$usuario_db = "admin";
$password_db = "PerroSanxeDimision";
$nombre_db = "CI_NA_Tickets";
$conexion = new mysqli($servidor, $usuario_db, $password_db, $nombre_db);
if ($conexion->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conexion->connect_error]));
}
$conexion->set_charset("utf8");
?>
