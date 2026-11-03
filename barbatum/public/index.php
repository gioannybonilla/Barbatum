<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';

$stmt = $pdo->query("SELECT * FROM servicios WHERE activo = 1 ORDER BY nombre ASC");
$servicios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - Barbatum</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <header class="cabecera">
        <h1>💈 Barbatum</h1>
        <p>Elige tu servicio</p>
        <nav class="navegacion">
            <a href="index.php" class="activo">Servicios</a>
            <a href="barberos.php">Barberos</a>
            <a href="administrador/servicios.php?admin_view=1">Administración</a>
        </nav>
    </header>

    <main class="contenedor">
        <h2>Nuestros Servicios</h2>

        <?php if (empty($servicios)): ?>
            <p style="text-align: center; padding: 2rem; color: #666;">No hay servicios disponibles.</p>
        <?php else: ?>
            <div class="catalogo">
                <?php foreach ($servicios as $s): ?>
                <div class="tarjeta">
                    <h3><?= htmlspecialchars($s['nombre']) ?></h3>
                    <p><?= htmlspecialchars($s['descripcion']) ?></p>
                    <p class="precio">$<?= number_format($s['precio'], 0, ',', '.') ?></p>
                    <p class="duracion">⏱ <?= $s['duracion'] ?> minutos</p>
                    <a href="seleccionar-servicio.php?id=<?= $s['id'] ?>" class="btn btn-primario">Seleccionar</a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="assets/js/app.js"></script>
</body>
</html>