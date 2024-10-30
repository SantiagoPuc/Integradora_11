<?php
require_once(__DIR__ . '/../bd/conexion.php');
require_once(__DIR__ . '/../model/index_model.php');

class index_controller {
    private $model;

    public function __construct() {
        $this->model = new index_model();
    }

    public function index() {
        $productos = $this->model->getProductos();
        include_once(__DIR__ . '/../view/index.php');
    }

    public function filtrarPorCategoria($categoriaId) {
        $productos = $this->model->getProductosPorCategoria($categoriaId);
        echo json_encode($productos); // Enviar los productos como JSON
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'filtrarPorCategoria') {
    $controller = new index_controller();
    $categoriaId = intval($_GET['categoriaId']);
    $controller->filtrarPorCategoria($categoriaId);
}


?>