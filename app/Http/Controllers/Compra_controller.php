<?php
require_once(__DIR__ . '/../model/Compra_model.php');

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];
    switch ($action) {
        case 'generar_folio_compra':
            generarfolio_compra();
            break;
        case 'obtener_id_compra':
            obtenerid_compra();
            break;
        case 'obtener_no_existencias':
            obtenerno_existencias();
            break;
        case 'actualizar_existencias_producto':
            actualizarexistencias_producto();
            break;
        case 'mostrar_informacion_compra':
            mostrarinformacion_compra();
            break;
        case 'mostrar_detalle_compra':
            mostrardetalle_compra();
            break;
        case 'buscar_proveedor':
            buscarProveedor();
            break;
        case 'buscar_producto':
            buscarProducto();
            break;
        case 'obtener_todas_las_compras':
            obtenerTodasLasCompras();
            break;
        case 'obtener_todos_los_detalles_de_compras':
            obtenerTodosLosDetallesDeCompras();
            break;
        case 'buscar_compras_por_folio':
            buscarComprasPorFolio();
            break;
        case 'obtener_sugerencias_folio':
            if (isset($_GET['id_compra'])) {
                $query = $_GET['id_compra'];
                $model = new Compra_model();
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

function obtenerTodasLasCompras() {
    $model = new Compra_model();
    $compras = $model->obtener_todas_las_compras();
    echo json_encode($compras);
}

function obtenerTodosLosDetallesDeCompras() {
    $model = new Compra_model();
    $detalles = $model->obtener_todos_los_detalles_de_compras();
    echo json_encode($detalles);
}

function buscarComprasPorFolio() {
    if (isset($_GET['folio'])) {
        $folio = $_GET['folio'];
        $model = new Compra_model();
        $compras = $model->buscarComprasPorFolio($folio);
        echo json_encode($compras);
    } else {
        echo json_encode([]);
    }
}

function generarfolio_compra() {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id_proveedor']) && isset($data['productos'])) {
            if (isset($_SESSION['user'])) {
                $id_usuario = $_SESSION['user']['id'];  // Obtén el ID del usuario de la sesión
            } else {
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                return;
            }            $id_proveedor = $data['id_proveedor'];
            $productos = $data['productos'];
            $model = new Compra_model();
            $success = $model->generar_folio_compra($id_usuario, $id_proveedor, $productos);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        }
    } catch (Exception $e) {
        error_log('Error en generarfolio_compra: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function obtenerid_compra() {
    $model = new Compra_model();
    $id = $model->obtener_id_compra();
    echo json_encode(['id' => $id]);
}

function obtenerno_existencias() {
    if (isset($_GET['id_producto'])) {
        $id_producto = $_GET['id_producto'];
        $model = new Compra_model();
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
        $model = new Compra_model();
        $model->actualizar_existencias_producto($id_producto, $cantidad);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    }
}

function mostrarinformacion_compra() {
    $model = new Compra_model();
    $id_compra = isset($_GET['id_compra']) ? $_GET['id_compra'] : null;
    $compras = $model->mostrar_informacion_compra($id_compra);
    echo json_encode($compras);
}

function mostrardetalle_compra() {
    $model = new Compra_model();
    $id_compra = isset($_GET['id_compra']) ? $_GET['id_compra'] : null;
    $detalles = $model->mostrar_detalle_compra($id_compra);
    echo json_encode($detalles);
}

function buscarProveedor() {
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
        $model = new Compra_model();
        $proveedores = $model->buscar_proveedor($query);
        if ($proveedores === false) {
            echo json_encode(["error" => "Database query failed"]);
        } else {
            echo json_encode($proveedores);
        }
    } else {
        echo json_encode([]);
    }
}



function buscarProducto() {
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
        $model = new Compra_model();
        $productos = $model->buscar_producto($query);
        if ($productos === false) {
            echo json_encode(["error" => "Database query failed"]);
        } else {
            echo json_encode($productos);
        }
    } else {
        echo json_encode([]);
    }
}

class Compra_controller {
    private $model;

    public function __construct() {
        $this->model = new Compra_model();
    }

    public function index() {
        try {
            $informacionCompras = $this->model->mostrar_informacion_compra();
            $detalleCompras = $this->model->mostrar_detalle_compra();

            include_once(__DIR__ . '/../view/header.php');
            include_once(__DIR__ . '/../view/Adm_Compras.php');
            include_once(__DIR__ . '/../view/footer.php');
        } catch (Exception $e) {
            error_log('Index method failed: ' . $e->getMessage());
            die('Index method failed: ' . $e->getMessage());
        }
    }
}
?>
