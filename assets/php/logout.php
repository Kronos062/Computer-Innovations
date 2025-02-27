<?php
session_start();
session_destroy();
echo json_encode(["mensaje" => "Cerrón Sesiada"]);
?>
