<?php
    session_start();

    //Imports
    include '../../conexion/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.I. - Lista de tickets</title>
    <link rel="stylesheet" href="../css/styles-tickets.css">
</head>
<body>
<?php
    //variables
    if (isset($_SESSION['cliente_id'])) {
        $id_cliente = $_SESSION['cliente_id'];
        $id_empleado = NULL;
    } elseif (isset($_SESSION['empleado_id'])) {
        $id_empleado = $_SESSION['empleado_id'];
        $id_cliente = NULL;
    } else {
        echo "<p>No has iniciado sesión.</p>";
        echo "<a href='../../login/html/login.html'>Iniciar Sesión</a>";
    }
    $sesionCliente = NULL;
    $sesionEmpleado = NULL;

    if ($id_cliente !== NULL){
        $sesionCliente = 'id_cliente';
        $id = $id_cliente;
    } elseif ($id_empleado !== NULL) {
        $sesionEmpleado = 'id_empleado';
        $id = $id_empleado;
    }
    $variableSesion = $sesionCliente ?? $sesionEmpleado;

    if ($variableSesion == "id_empleado"){
        $query = "SELECT id_ticket, asunto, descripcion, solucion, estado, visitado, fechaEmision FROM CI_NA_Tickets.Tickets WHERE $variableSesion = ? ORDER BY visitado ASC, FIELD(estado, 'Abierto', 'Solucionado', 'Devuelto'), fechaEmision ASC";
    } else {
        $query = "SELECT id_ticket, asunto, descripcion, solucion, estado, fechaEmision FROM CI_NA_Tickets.Tickets WHERE $variableSesion = ? ORDER BY FIELD(estado, 'Abierto', 'Solucionado', 'Devuelto'), fechaEmision ASC";
    }
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $resultat = $stmt->get_result();
?>
    <header id="main-header">
        <div class="header-container">
            <div class="logo-container">
                <img src="../img/svg-logo.svg" alt="Logo" class="logo">
            </div>
            <nav>
                <div class="buttons-header">
                <ul>
                    <li><a class="main-btn" href="../../main/html/main.html">Pagina Principal</a></li>
                    <li><a class="descargar-btn" href="../../descarga/html/descarga.html">Descargar</a></li>
                    <li><a class="login-btn" href="../../login/html/login.html">Iniciar Sesión</a></li>
                </ul>
                </div>
            </nav>
        </div>
    </header>
    <h2>Gestiona tus tickets</h2>
    <div id='listaTickets'>
        <?php
        $contadorTickets = 1;
        if ($resultat->num_rows <= 0) {
            echo "<p>No hay tickets encontrados para este usuario.</p>";
            echo "<a href='../../crearTicket/html/crearTicket.html'>Crear Ticekt</a>";
        } else {        
            while ($row = $resultat->fetch_assoc()) {
                $_SESSION['tickets'][$row['id_ticket']] = $row;

                echo "
                <div id={$row['id_ticket']}>
                <h2>{$contadorTickets}. {$row['asunto']}</h2>
                <p>Estado del ticket: {$row['estado']}</p>
                <form method='POST' action='detalles.php'>
                    <input type='hidden' name='id_ticket' value={$row['id_ticket']}>
                    <input type='submit' name='detalles' value='Detalles'>
                </form>
                </br>
                </div>
                ";
                $contadorTickets += 1;
            }
        }
            ?>
    </div>
    <a href="crearTicket.html" class="button-class">
</body>
</html>