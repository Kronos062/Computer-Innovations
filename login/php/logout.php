<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();

header('Content-Type: application/json');
echo json_encode(["mensaje" => "Cerrón Sesiada"]);
?>
