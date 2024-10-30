<?php
require_once(__DIR__ . '/../model/Adm_reporte_model.php');

class Adm_reporte_controller {
    private $model;

    public function __construct() {
        $this->model = new Adm_reporte_model();
    }

    public function index() {
        try {
            $reporte1 = $this->model->getReporte1();
            $reporte2 = $this->model->getReporte2();
            $reporte3 = $this->model->getReporte3();

            include_once(__DIR__ . '/../view/header.php');
            include_once(__DIR__ . '/../view/Adm_Reportes.php');
            include_once(__DIR__ . '/../view/footer.php');
        } catch (Exception $e) {
            error_log('Index method failed: ' . $e->getMessage());
            die('Index method failed: ' . $e->getMessage());
        }
    }

    public function calcular_inventario() {
        try {
            // Obtener datos del formulario
            $codigoProducto = $_GET['codigo_producto'];
            $precio = $_GET['precio'];
            $precioCompra = $_GET['precio_compra'];
            $costoAlmacenamiento = $_GET['cs'];

            // Obtener demanda mensual
            $demandaMensual = $this->model->getConsumoPromedio($codigoProducto);

            // Calcular la función de ganancia neta
            $stock = range(0, 200, 5); // Aumentar el rango y número de puntos
            $ganancia = [];
            $ultimoValorPositivo = null;

            foreach ($stock as $s) {
                $g = $demandaMensual * ($precio - $precioCompra) - $s * $costoAlmacenamiento;
                if ($g >= 0) {
                    $ganancia[] = $g;
                    $ultimoValorPositivo = ['stock' => $s, 'ganancia' => $g];
                } else {
                    break;
                }
            }

            // Guardar resultados en variables para la vista
            $resultados = [
                'stock' => array_slice($stock, 0, count($ganancia)),
                'ganancia' => $ganancia,
                'demandaMensual' => $demandaMensual,
                'ultimoValorPositivo' => $ultimoValorPositivo
            ];

            // Incluir las vistas y pasar los datos
            include_once(__DIR__ . '/../view/header.php');
            include_once(__DIR__ . '/../view/Adm_Reportes.php');
            include_once(__DIR__ . '/../view/footer.php');

        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getReporte1() {
        $data = $this->model->getReporte1();
        echo json_encode($data);
    }

    public function getReporte2() {
        $data = $this->model->getReporte2();
        echo json_encode($data);
    }

    public function getReporte3() {
        $data = $this->model->getReporte3();
        echo json_encode($data);
    }

    public function buscar_producto() {
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $data = $this->model->buscarProducto($query);
            echo json_encode($data);
        } else {
            echo json_encode([]);
        }
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new Adm_reporte_controller();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo json_encode(["error" => "Invalid action"]);
        error_log("Invalid action: $action");
    }
} else {
    $controller = new Adm_reporte_controller();
    $controller->index();
}
?>
