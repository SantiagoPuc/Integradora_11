<?php
require_once('../model/Adm_Proveedores_model.php');

header('Content-Type: application/json');

$query = isset($_GET['q']) ? $_GET['q'] : '';

$model = new Adm_Proveedores_model();

if ($query === '') {
    // Obtener todos los proveedores si la consulta está vacía
    $proveedores = $model->get_activos();
} else {
    // Realizar la búsqueda con la consulta proporcionada
    $proveedores = $model->buscar_proveedor($query);
}

echo json_encode($proveedores);
?>
