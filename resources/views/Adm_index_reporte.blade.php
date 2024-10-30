<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) && !isset($_SESSION['client'])) {
    header('Location: ../view/login_registro.php');
    exit();
}

require_once(__DIR__ . '/../bd/conexion.php');
require_once(__DIR__ . '/../controller/Adm_reporte_controller.php');

$controller = new Adm_reporte_controller();

if (!empty($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Método no encontrado: $action";
        $controller->index();
    }
} else {
    $controller->index();
}
?>
