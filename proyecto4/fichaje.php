
<?php
session_start();

if (!isset($_SESSION["worker_id"])) {
    header("Location: login_trabajador.php");
    exit();
}

include 'config.php';

$worker_id = $_SESSION["worker_id"];
$fecha = date("Y-m-d");

$stmt = $conn->prepare("SELECT nombre, dni FROM Trabajadores WHERE id = ?");
$stmt->execute([$worker_id]);
$trabajador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trabajador) {
    echo "Error: Trabajador no encontrado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["entrada"])) {
        $hora_entrada = date("H:i:s");

        try {
            $stmt = $conn->prepare("INSERT INTO Fichajes (trabajador_id, fecha, hora_entrada) VALUES (?, ?, ?)");
            $stmt->execute([$worker_id, $fecha, $hora_entrada]);
            header("Location: fichaje_confirmado.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST["salida"])) {
        $hora_salida = date("H:i:s");

        try {
            $stmt = $conn->prepare("UPDATE Fichajes SET hora_salida = ? WHERE trabajador_id = ? AND fecha = ? AND hora_salida IS NULL");
            $stmt->execute([$hora_salida, $worker_id, $fecha]);
            header("Location: fichaje_confirmado.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fichaje Diario</title>
    <link rel="stylesheet" href="styles/fichajes.css">
</head>
<body>
    <h2>Fichaje Diario: <?php echo htmlspecialchars($trabajador['nombre']) . " con DNI: " . htmlspecialchars($trabajador['dni']); ?></h2>
    <form method="POST">
        <input type="submit" name="entrada" value="Registrar Entrada">
        <input type="submit" name="salida" value="Registrar Salida">
    </form>
    <br>
    <a href="ver_fichajes.php">Ver Fichajes</a>
    <br>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
