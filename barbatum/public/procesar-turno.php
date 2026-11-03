<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$servicio_id = $_POST['servicio_id'] ?? 0;
$barbero_id = $_POST['barbero_id'] ?? 0;
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';

if (!$servicio_id || !$barbero_id || !$fecha || !$hora) {
    echo "<h2>❌ Faltan datos</h2>";
    echo "<a href='javascript:history.back()'>← Volver</a>";
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO turnos (servicio_id, barbero_id, fecha, hora, estado)
        VALUES (?, ?, ?, ?, 'pendiente')
    ");
    $stmt->execute([$servicio_id, $barbero_id, $fecha, $hora]);
    
    $mensaje = "✅ ¡Turno confirmado! Te esperamos el $fecha a las $hora";
    $clase = "exito";
} catch (PDOException $e) {
    $mensaje = "❌ Error al guardar: " . $e->getMessage();
    $clase = "error";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación - Barbatum</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <style>
        .exito { color: #28a745; font-size: 1.2rem; text-align: center; padding: 2rem; }
        .error { color: #dc3545; font-size: 1.2rem; text-align: center; padding: 2rem; }
    </style>
</head>
<body>
    <header class="cabecera">
        <h1>💈 Barbatum</h1>
    </header>
    <main class="contenedor">
        <div class="tarjeta" style="max-width: 500px; margin: 2rem auto;">
            <p class="<?= $clase ?>"><?= $mensaje ?></p>
            <a href="index.php" class="btn btn-primario">Volver a Servicios</a>
        </div>
    </main>
</body>
</html>