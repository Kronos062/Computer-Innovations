<?php
$host = 'localhost';
$db = 'CI_NA_Tickets';
$user = 'client';
$pass = '1234';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die('Conexión fallida: ' . $mysqli->connect_error);
}
?>
