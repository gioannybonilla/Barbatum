<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';

$stmt = $pdo->query("SELECT * FROM barberos WHERE activo = 1 ORDER BY nombre");
$barberos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbatum - Barberos</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <header class="cabecera">
        <h1>💈 Barbatum</h1>
        <p>Nuestro equipo</p>
        <nav class="navegacion">
            <a href="index.php">Servicios</a>
            <a href="barberos.php" class="activo">Barberos</a>
            <a href="seleccionar-servicio.php?barbero_id=<?= $b['id'] ?>" class="btn btn-primario">Agendar cita</a>
        </nav>
    </header>

    <main class="contenedor">
        <h2>Nuestros Barberos</h2>
        
        <?php if (empty($barberos)): ?>
            <p>No hay barberos registrados.</p>
        <?php else: ?>
            <div class="catalogo">
                <?php foreach ($barberos as $b): ?>
                <div class="tarjeta">
                    <h3><?= htmlspecialchars($b['nombre']) ?></h3>
                    <p><?= htmlspecialchars($b['descripcion'] ?? 'Profesional dedicado a tu cuidado') ?></p>
                    <p class="duracion">✅ Disponible</p>
                    <a href="index.php" class="btn btn-primario">Agendar cita</a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>