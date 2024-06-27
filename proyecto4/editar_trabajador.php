<?php
session_start();

if (!isset($_SESSION["empresa_id"])) {
    header("Location: login_empresa.php");
    exit();
}

include 'config.php';

// Verificar si se ha enviado el formulario de editar trabajador
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trabajador_id = $_POST["trabajador_id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    try {
        // Actualizar los datos del trabajador
        $stmt = $conn->prepare("UPDATE Trabajadores SET nombre = ?, apellido = ?, direccion = ?, telefono = ?, email = ? WHERE id = ?");
        $stmt->execute([$nombre, $apellido, $direccion, $telefono, $email, $trabajador_id]);

        // Verificar si se solicitó renovar la contraseña
        if (isset($_POST["renovar_contrasena"])) {
            // Establecer la contraseña del trabajador como vacía (NULL en la base de datos)
            $stmt = $conn->prepare("UPDATE Trabajadores SET contraseña = NULL WHERE id = ?");
            $stmt->execute([$trabajador_id]);
        }

        // Redireccionar a la página de gestión de trabajadores u otra página relevante
        header("Location: gestion_trabajadores.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Obtener el ID del trabajador desde la URL (o desde donde sea que lo estés obteniendo)
$trabajador_id = $_GET['id'];

// Obtener los datos actuales del trabajador para prellenar el formulario
try {
    $stmt = $conn->prepare("SELECT * FROM Trabajadores WHERE id = ?");
    $stmt->execute([$trabajador_id]);
    $trabajador = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trabajador) {
        echo "Error: Trabajador no encontrado.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Trabajador</title>
    <link rel="stylesheet" href="styles/editar.css">
</head>
<body>
    <h2>Editar Trabajador</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="trabajador_id" value="<?php echo $trabajador['id']; ?>">
        
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($trabajador['nombre']); ?>" required><br><br>
        
        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($trabajador['apellido']); ?>" required><br><br>
        
        <label for="direccion">Dirección:</label><br>
        <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($trabajador['direccion']); ?>" required><br><br>
        
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($trabajador['telefono']); ?>" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($trabajador['email']); ?>" required><br><br>
        
        <!-- Botón para renovar contraseña -->
        <input type="submit" name="renovar_contrasena" value="Renovar Contraseña">
        
        <input type="submit" value="Guardar Cambios">
    </form>
    <br>
    <a href="gestion_trabajadores.php">Volver</a>
    <br>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
