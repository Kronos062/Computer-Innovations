<?php
session_start();

include '../../conexion/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.I. - Lista de tickets</title>
    <link rel="stylesheet" href="../css/styles-tiketing.css">
</head>
<body>
<?php
    if(isset($_POST['id_ticket']) && isset($_SESSION['tickets'][$_POST['id_ticket']])) {
        $ticket = $_SESSION['tickets'][$_POST['id_ticket']];
        
        if (isset($ticket['visitado']) && $ticket['visitado'] === false) {
            try {
                $query = "UPDATE Tickets SET visitado = true WHERE id_ticket = ?";
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("i", $_POST['id_ticket']);
                $stmt->execute();
            } catch (Exception){
                echo "<h3>No se ha podido marcar como visitado este ticket.</h3>";
            }
        }
    } else {
        echo "<p>Ticket no encontrado.</p>";
        exit;
    }
?>
    <div>
    <h2><?= $ticket['asunto']?></h2>
    <h3>ID: <?= $ticket['id_ticket']?></h3>
    <p><strong>Descrpicion:</stong> <?= $ticket['descripcion']?></p>
    <p><strong>Estado:</strong> <?= $ticket['estado']?></p>
    <p><strong>Fecha de emisión:</strong> <?= $ticket['fechaEmision']?></p>
    <p><strong>Solcuion:</strong> <?= $ticket['solucion'] ?? "Sin solución todavía."?></p>
    </br>
    </div>
    <form method="POST" action="editarTicket.php">
        <input type="hidden" name="id_ticket" value="<?=$ticket['id_ticket']?>">
        <?php if (isset($_SESSION['empleado_id'])) {
            echo "<textarea name='solucion' cols=60 rows=6 placeholde='Solución'></textarea>";
            echo "<button type='submit' name='accion' value='solucionar'>Cambiar solución</button>";
            echo "<button type='submit' name='accion' value='devolver'>Devolver</button>";
            echo "<button type='submit' name='accion' value='borrar'>Borrar</button>";
    }
    ?>
    </form>
<body>
