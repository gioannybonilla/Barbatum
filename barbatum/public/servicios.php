<?php
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/../../config/session.php';

if (!es_admin()) {
    die("Acceso denegado");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $stmt = $pdo->prepare("UPDATE servicios SET activo = 0 WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    } elseif (isset($_POST['guardar'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $duracion = $_POST['duracion_minutos'];
        
        if ($_POST['id']) {
            $stmt = $pdo->prepare("UPDATE servicios SET nombre=?, descripcion=?, precio=?, duracion_minutos=? WHERE id=?");
            $stmt->execute([$nombre, $descripcion, $precio, $duracion, $_POST['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, precio, duracion_minutos, activo) VALUES (?, ?, ?, ?, 1)");
            $stmt->execute([$nombre, $descripcion, $precio, $duracion]);
        }
    }
}

$stmt = $pdo->query("SELECT * FROM servicios ORDER BY activo DESC, nombre");
$servicios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Servicios</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>

        <nav style="margin-top: 1rem;">
            <a href="servicios.php" style="color: #d4a574; margin: 0 0.5rem;">Servicios</a>
            <a href="barberos.php" style="color: #d4a574; margin: 0 0.5rem;">Barberos</a>
            <a href="../index.php" style="color: #d4a574; margin: 0 0.5rem;">← Volver al sitio</a>
        </nav>
<body>
    <header class="cabecera">
        <h1>⚙️ Administración - Servicios</h1>
        <a href="../index.php" style="color: #d4a574;">← Volver al sitio</a>
    </header>
    <main class="contenedor">
        <h2>Agregar / Editar Servicio</h2>
        <form method="POST" class="tarjeta" style="max-width: 600px; margin-bottom: 2rem;">
            <input type="hidden" name="id" value="">
            <div style="margin: 0.5rem 0;">
                <label>Nombre:</label>
                <input type="text" name="nombre" required style="width: 100%; padding: 0.5rem;">
            </div>
            <div style="margin: 0.5rem 0;">
                <label>Descripción:</label>
                <textarea name="descripcion" style="width: 100%; padding: 0.5rem;"></textarea>
            </div>
            <div style="margin: 0.5rem 0;">
                <label>Precio:</label>
                <input type="number" name="precio" required style="width: 100%; padding: 0.5rem;">
            </div>
            <div style="margin: 0.5rem 0;">
                <label>Duración (minutos):</label>
                <input type="number" name="duracion_minutos" required style="width: 100%; padding: 0.5rem;">
            </div>
            <button type="submit" name="guardar" class="btn btn-primario">Guardar</button>
        </form>

        <h2>Lista de Servicios</h2>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden;">
            <tr style="background: #2c1810; color: white;">
                <th style="padding: 0.8rem;">Nombre</th>
                <th style="padding: 0.8rem;">Precio</th>
                <th style="padding: 0.8rem;">Duración</th>
                <th style="padding: 0.8rem;">Estado</th>
                <th style="padding: 0.8rem;">Acciones</th>
            </tr>
            <?php foreach ($servicios as $s): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 0.8rem;"><?= htmlspecialchars($s['nombre']) ?></td>
                <td style="padding: 0.8rem;">$<?= number_format($s['precio'], 0, ',', '.') ?></td>
                <td style="padding: 0.8rem;"><?= $s['duracion_minutos'] ?> min</td>
                <td style="padding: 0.8rem;"><?= $s['activo'] ? '✅ Activo' : '❌ Inactivo' ?></td>
                <td style="padding: 0.8rem;">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
                        <button type="submit" name="eliminar" style="background:#dc3545; color:white; border:none; padding:0.3rem 0.6rem; border-radius:4px; cursor:pointer;">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>