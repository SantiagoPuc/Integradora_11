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

require_once('../bd/conexion.php');
require_once(__DIR__ . '/../controller/Adm_Proveedores_controller.php');

$controller = new Adm_Proveedores_controller();

if (!empty($_REQUEST['m'])) {
    $metodo = $_REQUEST['m'];
    if (method_exists($controller, $metodo)) {
        $controller->$metodo();
    } else {
        $controller->index();
    }
} else {
    $controller->index();
}
?>
