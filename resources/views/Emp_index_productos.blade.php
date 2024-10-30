<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) && !isset($_SESSION['client'])) {
    header('Location: ../view/login_registro.php');
    exit();
}


require_once(__DIR__ . '/../bd/conexion.php');

require_once(__DIR__ . '/../controller/Adm_Productos_controller.php');

$controller = new Adm_Productos_controller();

if (!empty($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        $controller->index_emp();
    }
} else {
    $controller->index_emp();
}
?>