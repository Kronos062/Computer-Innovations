<?php
$servidor = "localhost";
$usuario = "root"; //tengo que poner aqui el usuario
$clave = ""; //creo que no tiene contraseña el xamp de default
$base_datos = "computer-innovations";//supongo se llamará así, sino ya lo cambio después

$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
