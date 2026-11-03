<?php
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/../../config/session.php';

if (!es_admin()) {
    die("Acceso denegado");
}

// Cambiar estado del turno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $stmt = $pdo->prepare("UPDATE turnos SET estado = ? WHERE id = ?");
    $stmt->execute([$_POST['nuevo_estado'], $_POST['turno_id']]);
    header("Location: turnos.php");
    exit;
}

// Consultar todos los turnos
$stmt = $pdo->query("
    SELECT t.*, s.nombre as servicio_nombre, b.nombre as barbero_nombre
    FROM turnos t
    JOIN servicios s ON t.servicio_id = s.id
    JOIN barberos b ON t.barbero_id = b.id
    ORDER BY t.fecha DESC, t.hora DESC
");
$turnos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Turnos Agendados</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <header class="cabecera">
        <h1>📋 Turnos Agendados</h1>
        <nav style="margin-top: 1rem;">
            <a href="servicios.php" style="color: #d4a574; margin: 0 0.5rem;">Servicios</a>
            <a href="barberos.php" style="color: #d4a574; margin: 0 0.5rem;">Barberos</a>
            <a href="turnos.php" style="color: #fff; font-weight: bold; margin: 0 0.5rem;">Turnos</a>
            <a href="../index.php" style="color: #d4a574; margin: 0 0.5rem;">← Volver al sitio</a>
        </nav>
    </header>

    <main class="contenedor">
        <h2>Lista de Turnos</h2>

        <?php if (empty($turnos)): ?>
            <p style="text-align: center; padding: 2rem; color: #666;">No hay turnos agendados todavía.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; margin-top: 1rem;">
                <tr style="background: #2c1810; color: white;">
                    <th style="padding: 0.8rem;">Fecha</th>
                    <th style="padding: 0.8rem;">Hora</th>
                    <th style="padding: 0.8rem;">Servicio</th>
                    <th style="padding: 0.8rem;">Barbero</th>
                    <th style="padding: 0.8rem;">Estado</th>
                    <th style="padding: 0.8rem;">Acción</th>
                </tr>
                <?php foreach ($turnos as $t): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 0.8rem;"><?= date('d/m/Y', strtotime($t['fecha'])) ?></td>
                    <td style="padding: 0.8rem;"><?= date('H:i', strtotime($t['hora'])) ?></td>
                    <td style="padding: 0.8rem;"><?= htmlspecialchars($t['servicio_nombre']) ?></td>
                    <td style="padding: 0.8rem;"><?= htmlspecialchars($t['barbero_nombre']) ?></td>
                    <td style="padding: 0.8rem;">
                        <?php
                        $estilos = [
                            'pendiente' => 'background:#fff3cd; color:#856404;',
                            'confirmado' => 'background:#d4edda; color:#155724;',
                            'cancelado' => 'background:#f8d7da; color:#721c24;'
                        ];
                        $clase = $estilos[$t['estado']] ?? '';
                        ?>
                        <span style="padding: 0.3rem 0.6rem; border-radius: 4px; <?= $clase ?>">
                            <?= ucfirst($t['estado']) ?>
                        </span>
                    </td>
                    <td style="padding: 0.8rem;">
                        <form method="POST" style="display: flex; gap: 0.3rem;">
                            <input type="hidden" name="turno_id" value="<?= $t['id'] ?>">
                            <select name="nuevo_estado" style="padding: 0.3rem; border-radius: 4px; border: 1px solid #ccc;">
                                <option value="pendiente" <?= $t['estado']==='pendiente'?'selected':'' ?>>Pendiente</option>
                                <option value="confirmado" <?= $t['estado']==='confirmado'?'selected':'' ?>>Confirmado</option>
                                <option value="cancelado" <?= $t['estado']==='cancelado'?'selected':'' ?>>Cancelado</option>
                            </select>
                            <button type="submit" name="cambiar_estado" class="btn btn-primario" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">✓</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>