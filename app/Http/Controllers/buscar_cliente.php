<?php
require_once('../model/Adm_Clientes_model.php');

header('Content-Type: application/json');

$query = isset($_GET['q']) ? $_GET['q'] : '';

$model = new Adm_Clientes_model();

if ($query === '') {
    // Obtener todos los clientes si la consulta está vacía
    $clientes = $model->get();
} else {
    // Realizar la búsqueda con la consulta proporcionada
    $clientes = $model->buscar_cliente($query);
}

echo json_encode($clientes);
?>
