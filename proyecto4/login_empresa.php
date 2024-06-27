<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    include 'config.php'; // Incluir archivo de configuración de la base de datos

    $email = $_POST["email"];
    $pass = $_POST["password"];

    try {
        $stmt = $conn->prepare("SELECT id, nombre,pass FROM Empresas WHERE email = ?");
        $stmt->execute([$email]);
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($empresa && password_verify($password, $empresa["pass"])) {
            $_SESSION["empresa_id"] = $empresa["id"];
            $_SESSION["empresa_nombre"] = $empresa["nombre"];
            header("Location: gestion_trabajadores.php");
            exit();
        } else {
            $error_message = "Credenciales incorrectas. Por favor, intenta de nuevo.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<?php include 'menu/menu.php'; ?>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Empresa</title>
    <link rel="stylesheet" href="styles/loginEmpresa.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión - Empresa</h2>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" name="submit" value="Iniciar Sesión">
            <button><a href="registro_empresa.php">Registrar Empresa</a></button>
        </form>
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
