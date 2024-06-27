
<?php
session_start();

if (!isset($_SESSION["worker_id"])) {
    header("Location: login_trabajador.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichaje Confirmado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Fichaje Confirmado</h2>
        <p>El fichaje ha sido registrado correctamente.</p>
        <p><a href="fichaje.php">Volver al registro de fichaje</a></p>
        <p><a href="logout.php">Cerrar sesión</a></p>
    </div>
</body>
</html>
