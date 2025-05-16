<?php
    session_start();
    include '../../conexion/conexion.php';
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.I. - Lista de tickets</title>
    <link rel="stylesheet" href="../css/styles-tickets.css">
</head>
<body>
    <div class="window">
        <div class="header">Centro de Incidencias - Lista de Tickets</div>

        <div class="nav-bar">
            <a href="../../main/html/main.html">Página Principal</a>
            <a href="../../descarga/html/descarga.html">Descargar</a>
            <a href="../../login/html/login.html">Iniciar Sesión</a>
        </div>

        <h2 style="text-align:center;">Gestiona tus tickets</h2>

        <div id="listaTickets">
        <?php
            if (isset($_SESSION['cliente_id'])) {
                $id_cliente = $_SESSION['cliente_id'];
                $id_empleado = NULL;
            } elseif (isset($_SESSION['empleado_id'])) {
                $id_empleado = $_SESSION['empleado_id'];
                $id_cliente = NULL;
            } else {
                echo "<p>No has iniciado sesión.</p>";
                echo "<a href='../../login/html/login.html'>Iniciar Sesión</a>";
                exit;
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

            if ($variableSesion === "id_empleado") {
                $query = "SELECT id_ticket, asunto, descripcion, solucion, estado, visitado, fechaEmision FROM CI_NA_Tickets.Tickets WHERE $variableSesion = ? ORDER BY visitado ASC, FIELD(estado, 'Abierto', 'Solucionado', 'Devuelto'), fechaEmision ASC";
            } else {
                $query = "SELECT id_ticket, asunto, descripcion, solucion, estado, fechaEmision FROM CI_NA_Tickets.Tickets WHERE $variableSesion = ? ORDER BY FIELD(estado, 'Abierto', 'Solucionado', 'Devuelto'), fechaEmision ASC";
            }

            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultat = $stmt->get_result();

            $contadorTickets = 1;
            if ($resultat->num_rows <= 0) {
                echo "<p>No hay tickets encontrados para este usuario.</p>";
                echo "<a href='../../crearTicket/html/crearTicket.html'>Crear Ticket</a>";
            } else {
                while ($row = $resultat->fetch_assoc()) {
                    $_SESSION['tickets'][$row['id_ticket']] = $row;

                    echo "
                    <div class='ticket'>
                        <h3>{$contadorTickets}. {$row['asunto']}</h3>
                        <p><strong>Estado:</strong> {$row['estado']}</p>
                        <form method='POST' action='detalles.php'>
                            <input type='hidden' name='id_ticket' value='{$row['id_ticket']}'>
                            <input type='submit' name='detalles' value='Detalles'>
                        </form>
                    </div>
                    ";
                    $contadorTickets++;
                }
            }
        ?>
        </div>

        <div style="text-align:center; margin-top: 20px;">
            <a href="crearTicket.html" class="button-class">Crear Ticket</a>
        </div>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> Centro de Incidencias
        </div>
    </div>
</body>
</html>