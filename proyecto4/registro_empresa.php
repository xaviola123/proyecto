<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $password = $_POST["password"]; 

    include 'config.php';
    
    try {
        $conn->beginTransaction();

        // Generar el hash de la contraseña
        $hashed_password = password_hash($password,PASSWORD_DEFAULT); 
        $stmt = $conn->prepare("INSERT INTO Empresas (nombre, direccion, telefono, email, pass) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $direccion, $telefono, $email, $hashed_password]);

        $empresa_id = $conn->lastInsertId();

        

        $conn->commit();
        $_SESSION["empresa_id"] = $empresa_id;
        $_SESSION['empresa_nombre'] = $nombre; 
        header("Location: login_empresa.php");
        exit(); 
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Empresa</title>
    <link rel="stylesheet" href="styles/registroEmpresa.css">
   
</head>
<body>
    <h2>Registro de Nueva Empresa</h2>
    <form method="POST">
        <label for="nombre">Nombre de la Empresa:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br><br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Registrar Empresa">
    </form>
</body>
<script src="js/validar_registroEmpresa.js"></script>
</html>
