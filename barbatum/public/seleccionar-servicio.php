<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';

// Obtener el servicio seleccionado
$servicio_id = $_GET['id'] ?? 0;

// Consultar datos del servicio
$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ? AND activo = 1");
$stmt->execute([$servicio_id]);
$servicio = $stmt->fetch();

if (!$servicio) {
    echo "<h2>Servicio no encontrado</h2>";
    echo "<a href='index.php'>← Volver a servicios</a>";
    exit;
}

// Obtener barberos disponibles
$stmt_barberos = $pdo->query("SELECT * FROM barberos WHERE activo = 1 ORDER BY nombre");
$barberos = $stmt_barberos->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar - <?= htmlspecialchars($servicio['nombre']) ?></title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <header class="cabecera">
        <h1>💈 Barbatum</h1>
        <a href="index.php" style="color: #d4a574;">← Volver a Servicios</a>
    </header>

    <main class="contenedor">
        <h2>Agendar: <?= htmlspecialchars($servicio['nombre']) ?></h2>
        
        <div class="tarjeta" style="max-width: 600px; margin: 0 auto 2rem;">
            <h3><?= htmlspecialchars($servicio['nombre']) ?></h3>
            <p><?= htmlspecialchars($servicio['descripcion']) ?></p>
            <p class="precio">$ <?= number_format($servicio['precio'], 0, ',', '.') ?></p>
            <p class="duracion">⏱ <?= $servicio['duracion_minutos'] ?> minutos</p>
        </div>

        <h3>Elige tu barbero</h3>
        <?php if (empty($barberos)): ?>
            <p>No hay barberos disponibles en este momento.</p>
        <?php else: ?>
            <form method="POST" action="procesar-turno.php" style="max-width: 600px; margin: 0 auto;">
                <input type="hidden" name="servicio_id" value="<?= $servicio['id'] ?>">
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Barbero:</label>
                    <select name="barbero_id" required style="width: 100%; padding: 0.8rem; border-radius: 6px; border: 1px solid #ccc;">
                        <?php foreach ($barberos as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Fecha:</label>
                    <input type="date" name="fecha" required style="width: 100%; padding: 0.8rem; border-radius: 6px; border: 1px solid #ccc;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Hora:</label>
                    <input type="time" name="hora" required style="width: 100%; padding: 0.8rem; border-radius: 6px; border: 1px solid #ccc;">
                </div>

                <button type="submit" class="btn btn-primario">✅ Confirmar Turno</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>