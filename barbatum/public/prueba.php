<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "✅ PHP funciona <br>";

require '../config/db.php';

echo "✅ Conexión a la base de datos exitosa <br>";

$stmt = $pdo->query("SELECT COUNT(*) as total FROM servicios");
$fila = $stmt->fetch();
echo "✅ Servicios encontrados: " . $fila['total'];
?>