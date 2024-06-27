<?php
session_start();

// Verificar si el usuario tiene una sesión activa como empresa
if (!isset($_SESSION["empresa_id"])) {
    header("Location: login_empresa.php");
    exit();
}

include 'config.php'; // Incluir archivo de configuración de la base de datos

// Verificar si se ha pasado el ID del trabajador por GET
if (!isset($_GET["id"])) {
    echo "Error: ID de trabajador no especificado.";
    exit();
}

$trabajador_id = $_GET["id"];

// Consultar los datos del trabajador
try {
    $stmt = $conn->prepare("SELECT * FROM Trabajadores WHERE id = ?");
    $stmt->execute([$trabajador_id]);
    $trabajador = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trabajador) {
        echo "Error: No se encontró al trabajador.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error al obtener trabajador: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Consulta de Trabajador</title>
    <link rel="stylesheet" href="styles/consultar.css">
</head>
<body>
    <div class="container">
        <h2>Consulta de Trabajador</h2>
        
        <!-- Mostrar los datos del trabajador -->
        <p><strong>DNI:</strong> <?php echo htmlspecialchars($trabajador['dni']); ?></p>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($trabajador['nombre']); ?></p>
        <p><strong>Apellido:</strong> <?php echo htmlspecialchars($trabajador['apellido']); ?></p>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($trabajador['direccion']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($trabajador['telefono']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($trabajador['email']); ?></p>
        <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($trabajador['fecha_nacimiento']); ?></p>
        <p><strong>Fecha de Contratación:</strong> <?php echo htmlspecialchars($trabajador['fecha_contratacion']); ?></p>
        
       <a href="gestion_trabajadores.php"> <button>Volver a la Lista de Trabajadores</button></a>
    </div>
</body>
</html>
