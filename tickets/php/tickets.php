<?php
    session_start();
    include '../../conexion/conexion.php';
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>C.I. - Lista de tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles-tickets.css" />
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="controles" style="text-align:right; padding:10px;">
        <button id="btnSwitch" class="btn btn-outline-secondary">
            <ion-icon name="moon" style="font-size: 24px;"></ion-icon>
        </button>
    </div>

    <div class="window container my-4 p-4 border rounded shadow-sm">
        <div class="header mb-3 text-center fs-4 fw-bold">Centro de Incidencias - Lista de Tickets</div>

        <nav class="nav justify-content-center mb-4">
            <a class="nav-link" href="../../main/html/main.html">Página Principal</a>
            <a class="nav-link" href="../../descarga/html/descarga.html">Descargar</a>
            <a class="nav-link" href="../../login/html/login.html">Iniciar Sesión</a>
        </nav>

        <h2 class="text-center mb-4">Gestiona tus tickets</h2>

        <div id="listaTickets" class="list-group">
        <?php
            if (isset($_SESSION['cliente_id'])) {
                $id_cliente = $_SESSION['cliente_id'];
                $id_empleado = NULL;
            } elseif (isset($_SESSION['empleado_id'])) {
                $id_empleado = $_SESSION['empleado_id'];
                $id_cliente = NULL;
            } else {
                echo "<p class='text-center'>No has iniciado sesión.</p>";
                echo "<p class='text-center'><a href='../../login/html/login.html'>Iniciar Sesión</a></p>";
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
                echo "<p class='text-center'>No hay tickets encontrados para este usuario.</p>";
                echo "<p class='text-center'><a href='../../crearTicket/html/crearTicket.html'>Crear Ticket</a></p>";
            } else {
                while ($row = $resultat->fetch_assoc()) {
                    $_SESSION['tickets'][$row['id_ticket']] = $row;
                    echo "
                    <div class='list-group-item'>
                        <h5>{$contadorTickets}. {$row['asunto']}</h5>
                        <p><strong>Estado:</strong> {$row['estado']}</p>
                        <form method='POST' action='detalles.php'>
                            <input type='hidden' name='id_ticket' value='{$row['id_ticket']}' />
                            <input type='submit' name='detalles' class='btn btn-primary btn-sm' value='Detalles' />
                        </form>
                    </div>
                    ";
                    $contadorTickets++;
                }
            }
        ?>
        </div>

        <div class="text-center mt-4">
            <a href="../../crearTicket/html/crearTicket.html" class="btn btn-success">Crear Ticket</a>
        </div>

        <footer class="footer mt-5 text-center text-muted">
            &copy; <?php echo date("Y"); ?> Centro de Incidencias
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('btnSwitch').addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);

            const icon = document.querySelector('#btnSwitch ion-icon');
            icon.setAttribute('name', newTheme === 'dark' ? 'moon' : 'sunny');
        });
    </script>
</body>
</html>