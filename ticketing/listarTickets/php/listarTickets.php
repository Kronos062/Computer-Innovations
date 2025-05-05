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
    //Imports
    include '../../../conexion/conexion.php';

    session_start();

    //variables
    $id_empleado = $_SESSION['id_empleado'];
    $id_cliente = $_SESSION['id_cliente'];
    //$id_admin? = $_SESION['id_admin?'];
    if ($id_cliente !== NULL){
        $sesionCliente = 'id_cliente';
        $id_usuario = $id_cliente; 
    } elseif ($id_empleado !== NULL) {
        $sesionEmpleado = 'id_empleado';
        $id_usuario = $id_empleado;
    }// else {$sesionEmpleado = 'id_empleado'; $id_empleado = '*';}
    $variableSesion = $sesionCliente ?? $sesionEmpleado;

    //cambio en las variables para el test
    $id_cliente = 2;
    $variableSesion = 'id_cliente';
    //---
    $query = "SELECT id_ticket, asunto, descripcion, asunto FROM CI_NA_Tickets.Tickets WHERE $variableSesion = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_cliente );

    $stmt->execute();
    $resultat = $stmt->get_result();
?>
    <script>
        alertSolucion = () => alert("Ticket solucionado.");

        alertDevolver = () => alert("Ticket devuelto.");

        alertError = () => alert("Ha habido un error con tu solicitud.")
    </script>
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
        if ($resultat->num_rows === 0) {
            echo "<p>No hay tickets encontrados para este usuario.</p>";
        } else {        
            while ($row = $resultat->fetch_assoc()) {
                echo "
                <h3>{$row['id_ticket']}. {$row['asunto']}</h3>
                <p>{$row['descripcion']}</p>
                <p>Estado del ticket: {$row['estado']}</p>
                <form>
                    <button onclick='consultar()'>Consultar</button>
                    <button onclick='solucionar({$row['id_ticket']})'>Solucionar</button>
                    <button onclick='devolver({$row['id_ticket']})'>Devolver</button>
                </form>
                </br>
                ";
            }
        }
            ?>
    </div>
    <a href="crearTicket.html" class="button-class">
</body>
</html>