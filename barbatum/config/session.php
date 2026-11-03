<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['sesion_id'])) {
    $_SESSION['sesion_id'] = uniqid('barb_', true);
}

function es_admin() {
    return isset($_GET['admin_view']) || isset($_SESSION['es_admin']);
}
?>