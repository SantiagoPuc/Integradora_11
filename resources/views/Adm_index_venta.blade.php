<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) && !isset($_SESSION['client'])) {
    header('Location: ../view/login_registro.php');
    exit();
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../bd/conexion.php');
require_once(__DIR__ . '/../controller/Venta_controller.php');

$controller = new Venta_controller();

if (!empty($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        $controller->index();
    }
} else {
    $controller->index();
}
?>
