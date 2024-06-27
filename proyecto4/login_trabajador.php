<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST["dni"];
    $contraseña = $_POST["contraseña"];

    include 'config.php';

    try {
        $stmt = $conn->prepare("SELECT id, empresa_id, nombre, contraseña, primer_login FROM Trabajadores WHERE dni = ?");
        $stmt->execute([$dni]);
        $trabajador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($trabajador) {
            if ($trabajador["primer_login"] == 1 || $trabajador["contraseña"] === NULL) {
                $_SESSION["temp_worker_id"] = $trabajador["id"];
                header("Location: cambiar_contraseña.php");
                exit();
            } elseif (password_verify($contraseña, $trabajador["contraseña"])) {
                $_SESSION["worker_id"] = $trabajador["id"];
                $_SESSION["empresa_id"] = $trabajador["empresa_id"];
                $_SESSION["trabajador_nombre"] = $trabajador["nombre"];
                header("Location: fichaje.php");
                exit();
            } else {
                $error = "Contraseña incorrecta. Por favor, intenta de nuevo.";
            }
        } else {
            $error = "No se encontró ningún trabajador con el DNI proporcionado.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'menu/menu.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Trabajador</title>
    <link rel="stylesheet" href="styles/loginTrabajador.css">
</head>
<body>
    <div class="container">
        <h2>Login de Trabajador</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" >
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
