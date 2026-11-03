<?php
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/../../config/session.php';

if (!es_admin()) die("Acceso denegado");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $stmt = $pdo->prepare("UPDATE barberos SET activo = 0 WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    } elseif (isset($_POST['guardar'])) {
        if ($_POST['id']) {
            $stmt = $pdo->prepare("UPDATE barberos SET nombre=?, descripcion=? WHERE id=?");
            $stmt->execute([$_POST['nombre'], $_POST['descripcion'], $_POST['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO barberos (nombre, descripcion, activo) VALUES (?, ?, 1)");
            $stmt->execute([$_POST['nombre'], $_POST['descripcion']]);
        }
    }
}

$stmt = $pdo->query("SELECT * FROM barberos ORDER BY activo DESC, nombre");
$barberos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Barberos</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <header class="cabecera">
        <h1>⚙️ Administración - Barberos</h1>
        <a href="../index.php" style="color: #d4a574;">← Volver al sitio</a>
    </header>
    <main class="contenedor">
        <h2>Agregar Barbero</h2>
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
            <button type="submit" name="guardar" class="btn btn-primario">Guardar</button>
        </form>

        <h2>Lista de Barberos</h2>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px;">
            <tr style="background: #2c1810; color: white;">
                <th style="padding: 0.8rem;">Nombre</th>
                <th style="padding: 0.8rem;">Estado</th>
                <th style="padding: 0.8rem;">Acción</th>
            </tr>
            <?php foreach ($barberos as $b): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 0.8rem;"><?= htmlspecialchars($b['nombre']) ?></td>
                <td style="padding: 0.8rem;"><?= $b['activo'] ? '✅ Activo' : '❌ Inactivo' ?></td>
                <td style="padding: 0.8rem;">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                        <button type="submit" name="eliminar" style="background:#dc3545; color:white; border:none; padding:0.3rem 0.6rem; border-radius:4px; cursor:pointer;">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>