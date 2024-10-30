<?php
require_once(__DIR__ . '/../model/Venta_model.php');

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];
    switch ($action) {
        case 'generar_folio_venta':
            generarfolio_venta();
            break;
        case 'obtener_id_venta':
            obtenerid_venta();
            break;
        case 'obtener_no_existencias':
            obtenerno_existencias();
            break;
        case 'actualizar_existencias_producto':
            actualizarexistencias_producto();
            break;
        case 'mostrar_informacion_venta':
            mostrarinformacion_venta();
            break;
        case 'mostrar_detalle_venta':
            mostrardetalle_venta();
            break;
        case 'buscar_cliente':
            buscarCliente();
            break;
        case 'buscar_producto':
            buscarProducto();
            break;
        case 'obtener_todas_las_ventas':
            obtenerTodasLasVentas();
            break;
        case 'obtener_todos_los_detalles_de_ventas':
            obtenerTodosLosDetallesDeVentas();
            break;
        case 'buscar_ventas_por_folio':
            buscarVentasPorFolio();
            break;
        case 'obtener_sugerencias_folio':
            if (isset($_GET['id_venta'])) {
                $query = $_GET['id_venta'];
                $model = new Venta_model();
                $sugerencias = $model->obtener_sugerencias_folio($query);
                echo json_encode($sugerencias);
            } else {
                echo json_encode([]);
            }
            break;
        default:
            echo json_encode(["error" => "Invalid action"]);
            break;
    }
    exit;
}
function obtenerTodasLasVentas() {
    $model = new Venta_model();
    $ventas = $model->obtener_todas_las_ventas();
    echo json_encode($ventas);
}

function obtenerTodosLosDetallesDeVentas() {
    $model = new Venta_model();
    $detalles = $model->obtener_todos_los_detalles_de_ventas();
    echo json_encode($detalles);
}
function buscarVentasPorFolio() {
    if (isset($_GET['folio'])) {
        $folio = $_GET['folio'];
        $model = new Venta_model();
        $ventas = $model->buscarVentasPorFolio($folio);
        echo json_encode($ventas);
    } else {
        echo json_encode([]);
    }
}

function generarfolio_venta() {
    error_log('Datos recibidos: ' . file_get_contents('php://input')); // Log de los datos recibidos

    try {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id_cliente']) && isset($data['productos'])) {
            if (isset($_SESSION['user'])) {
                $id_usuario = $_SESSION['user']['id'];  // Obtén el ID del usuario de la sesión
            } else {
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                return;
            }            $id_cliente = $data['id_cliente'];
            $productos = $data['productos'];
            $model = new Venta_model();
            $success = $model->generar_folio_venta($id_usuario, $id_cliente, $productos);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        }
    } catch (Exception $e) {
        error_log('Error en generarfolio_venta: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}





function obtenerid_venta() {
    $model = new Venta_model();
    $id = $model->obtener_id_venta();
    echo json_encode(['id' => $id]);
}

function obtenerno_existencias() {
    if (isset($_GET['id_producto'])) {
        $id_producto = $_GET['id_producto'];
        $model = new Venta_model();
        $existencias = $model->obtener_no_existencias($id_producto);
        echo json_encode(['existencias' => $existencias]);
    } else {
        echo json_encode(['existencias' => 0]);
    }
}

function actualizarexistencias_producto() {
    if (isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
        $id_producto = $_POST['id_producto'];
        $cantidad = $_POST['cantidad'];
        $model = new Venta_model();
        $model->actualizar_existencias_producto($id_producto, $cantidad);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    }
}

function mostrarinformacion_venta() {
    $model = new Venta_model();
    $id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : null;
    $ventas = $model->mostrar_informacion_venta($id_venta);
    echo json_encode($ventas);
}

function mostrardetalle_venta() {
    $model = new Venta_model();
    $id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : null;
    $detalles = $model->mostrar_detalle_venta($id_venta);
    echo json_encode($detalles);
}

function buscarCliente() {
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
        $model = new Venta_model();
        $clientes = $model->buscar_cliente($query);
        echo json_encode($clientes);
    } else {
        echo json_encode([]);
    }
}

function buscarProducto() {
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
        $model = new Venta_model();
        $productos = $model->buscar_producto($query);
        echo json_encode($productos);
    } else {
        echo json_encode([]);
    }
}

class Venta_controller {
    private $model;

    public function __construct() {
        $this->model = new Venta_model();
    }
    
    
    
    public function index() {
        try {
            $informacionVentas = $this->model->mostrar_informacion_venta();
            $detalleVentas = $this->model->mostrar_detalle_venta();

            include_once(__DIR__ . '/../view/header.php');
            include_once(__DIR__ . '/../view/Adm_Ventas.php');
            include_once(__DIR__ . '/../view/footer.php');
        } catch (Exception $e) {
            error_log('Index method failed: ' . $e->getMessage());
            die('Index method failed: ' . $e->getMessage());
        }
    }

    public function index_emp() {
        try {
            $informacionVentas = $this->model->mostrar_informacion_venta();
            $detalleVentas = $this->model->mostrar_detalle_venta();

            include_once(__DIR__ . '/../view/header_emp.php');
            include_once(__DIR__ . '/../view/Adm_Ventas.php');
            include_once(__DIR__ . '/../view/footer.php');
        } catch (Exception $e) {
            error_log('Index method failed: ' . $e->getMessage());
            die('Index method failed: ' . $e->getMessage());
        }
    }
}
?>
