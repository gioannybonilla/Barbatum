<?php
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/../../config/session.php';

if (!es_admin()) die("Acceso denegado");

echo "<header class='cabecera'><h1>⚙️ Configuración de Horarios</h1><a href='../index.php' style='color:#d4a574;'>← Volver</a></header>";
echo "<main class='contenedor'><p>Página de configuración de horarios disponibles.</p>";
echo "<p>Aquí podrás definir los días y horas en que cada barbero atiende.</p></main>";
?>