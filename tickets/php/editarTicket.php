<?php
include '../../conexion/conexion.php';
session_start();

if (!isset($_POST['id_ticket']) || !isset($_POST['accion']) || !isset($_POST['solucion'])) {
    die("Faltan datos.");
}

$id_ticket = $_POST['id_ticket'];
$accion = $_POST['accion'];
$solucion = $_POST['solucion'];

switch ($accion) {
    case 'solucionar':
        solucionarTicket($conexion, $solucion, $id_ticket);
        break;
    case 'abrir':
        abrirTicket($conexion, $id_ticket);
        break;
    case 'devolver':
        devolverTicket($conexion, $id_ticket);
        break;
    case 'borrar':
        borrarTicket($conexion, $id_ticket);
        break;
    default:
        echo "Acción no reconocida.";
}

//Funciones
function solucionarTicket($conexion, $solucion, $id) {
    try {
        $stmt = $conexion->prepare("UPDATE Tickets SET estado = 'Solucionado', solucion = ? WHERE id_ticket = ?");
        $stmt->bind_param("si", $solucion, $id);
        $stmt->execute();
        header('Location: tickets.php');
    } catch (Exception) {
        header('Location: tickets.php#es');
    }
}

function abrirTicket($conexion, $id) {
    try {
        $stmt = $conexion->prepare("UPDATE Tickets SET estado = 'Abierto' WHERE id_ticket = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header('Location: tickets.php');
    } catch (Exception) {
        header('Location: tickets.php#ta');
    }
}

function devolverTicket($conexion, $id) {
    try {
        $stmt = $conexion->prepare("UPDATE Tickets SET estado = 'Devuelto' WHERE id_ticket = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header('Location: tickets.php');
    } catch (Exception) {
        header('Location: tickets.php#ed');
    }
}

function borrarTicket($conexion, $id) {
    try {
        $stmt = $conexion->prepare("DELETE FROM Tickets WHERE id_ticket = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header('Location: tickets.php');
    } catch (Exception) {
        header('Location: tickets.php#eb');
    }
}
?>