<?php
require_once(__DIR__ . '/../model/Adm_Proveedores_model.php');

// Habilita el logging de errores y deshabilita la salida de errores
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');
ini_set('display_errors', 0);

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];
    switch ($action) {
        case 'get_estados':
            getEstados();
            break;
        case 'get_ciudades':
            getCiudades();
            break;
        case 'get_paises':
            getPaises();
            break;
        case 'get_proveedor':
            getProveedor();
            break;
        case 'get_proveedor_full':
            getProveedorFull();
            break;
        case 'update_proveedor':
            $controller = new Adm_Proveedores_controller();
            $controller->update_proveedor();
            break;
        case 'toggle_estado_proveedor':
            $controller = new Adm_Proveedores_controller();
            $controller->toggle_estado_proveedor();
            break;
        case 'get_inactivos':
            getInactivos();
            break;
        case 'habilitar_proveedor':
            habilitarProveedor();
            break;
        case 'get_marcas':
            getMarcas();
            break;
        case 'buscar_proveedor':
            buscarProveedor();
            break;
        case 'get_proveedor_detalles':
            getProveedorDetalles();
            break;
        case 'check_rfc':
            checkRfc();
            break;            
        default:
            echo json_encode(["error" => "Invalid action"]);
            break;
    }
    exit;
}


function getEstados() {
    if (isset($_GET['id_pais'])) {
        $idPais = $_GET['id_pais'];
        $model = new Adm_Proveedores_model();
        $estados = $model->getEstadosByPais($idPais);
        echo json_encode($estados);
    } else {
        echo json_encode(["error" => "Missing id_pais parameter"]);
    }
}

function getCiudades() {
    if (isset($_GET['id_estado'])) {
        $idEstado = $_GET['id_estado'];
        $model = new Adm_Proveedores_model();
        $ciudades = $model->getCiudadesByEstado($idEstado);
        echo json_encode($ciudades);
    } else {
        echo json_encode(["error" => "Missing id_estado parameter"]);
    }
}

function getPaises() {
    $model = new Adm_Proveedores_model();
    $paises = $model->get_paises();
    echo json_encode($paises);
}

function getProveedorFull() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $model = new Adm_Proveedores_model();
        $proveedor = $model->get_proveedor_full($id);
        echo json_encode($proveedor);
    } else {
        echo json_encode(["error" => "Missing id parameter"]);
    }
}

function getMarcas() {
    $model = new Adm_Proveedores_model();
    $marcas = $model->get_marcas();
    if (is_array($marcas)) {
        echo json_encode($marcas);
    } else {
        echo json_encode([]);
    }
}

function getProveedor() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $model = new Adm_Proveedores_model();
        $proveedor = $model->get_proveedor($id);
        // Verificar los datos que se están enviando al frontend
        error_log("Datos del proveedor: " . print_r($proveedor, true));
        echo json_encode($proveedor);
    } else {
        echo json_encode(["error" => "Missing id parameter"]);
    }
}

function getInactivos() {
    $model = new Adm_Proveedores_model();
    $proveedores = $model->get_inactivos();
    echo json_encode($proveedores);
}

function habilitarProveedor() {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $model = new Adm_Proveedores_model();
        $success = $model->habilitar_proveedor($id);
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del proveedor no proporcionado.']);
    }
}

function buscarProveedor() {
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
        $model = new Adm_Proveedores_model();
        if ($query === '') {
            // Obtener todos los proveedores activos si la consulta está vacía
            $proveedores = $model->get_activos();
        } else {
            // Realizar la búsqueda con la consulta proporcionada
            $proveedores = $model->buscar_proveedor($query);
        }
        echo json_encode($proveedores);
    } else {
        echo json_encode(["error" => "Missing query parameter"]);
    }
}


function getProveedorDetalles() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $model = new Adm_Proveedores_model();
        $proveedor = $model->get_proveedor_detalles($id);
        echo json_encode($proveedor);
    } else {
        echo json_encode(["error" => "Missing id parameter"]);
    }
}


function checkRfc() {
    if (isset($_GET['rfc'])) {
        $rfc = $_GET['rfc'];
        $exclude_id = isset($_GET['exclude_id']) ? $_GET['exclude_id'] : null;
        $model = new Adm_Proveedores_model();
        $exists = $model->rfc_exists($rfc, $exclude_id);
        echo json_encode(['exists' => $exists]);
    } else {
        echo json_encode(["error" => "Missing RFC parameter"]);
    }
}





class Adm_Proveedores_controller {
    private $model;

    public function __construct() {
        $this->model = new Adm_Proveedores_model();
    }

    public function index() {
        try {
            $proveedores = $this->model->get_activos();
            $paises = $this->model->get_paises();
            $marcas = $this->model->get_marcas();
            
            include_once(__DIR__ . '/../view/header.php');
            include_once(__DIR__ . '/../view/Adm_Proveedores.php');
            include_once(__DIR__ . '/../view/footer.php');
        } catch (Exception $e) {
            error_log('Index method failed: ' . $e->getMessage());
            die('Index method failed: ' . $e->getMessage());
        }
    }

    public function add_proveedor() {
        try {
            $rfc = $_POST['rfc'];
            if ($this->model->rfc_exists($rfc)) {
                throw new Exception('El RFC ya está en uso por otro proveedor.');
            }
    
            $foto = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $fotoTmpPath = $_FILES['foto']['tmp_name'];
                $fotoExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = "proveedor-{$_POST['nombre']}-{$_POST['primer_apellido']}.$fotoExtension";
                move_uploaded_file($fotoTmpPath, __DIR__ . "/../uploads/proveedoresimg/$foto");
            }
            else{
                $foto = '0.jpg';
            }
    
            $data = [
                'nombre' => $_POST['nombre'],
                'primer_apellido' => $_POST['primer_apellido'],
                'segundo_apellido' => $_POST['segundo_apellido'],
                'telefono' => $_POST['telefono'],
                'rfc' => $rfc,
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'codigo_postal' => $_POST['codigo_postal'],
                'cruzamientos' => $_POST['cruzamientos'],
                'colonia' => $_POST['colonia'],
                'id_ciudad' => $_POST['ciudad'],
                'foto' => $foto,
                'marcas' => $_POST['marcas']
            ];
            $this->model->add_proveedor($data);
            header("Location: Adm_index_Proveedores.php");
        } catch (Exception $e) {
            error_log('Add proveedor method failed: ' . $e->getMessage());
            die('Add proveedor method failed: ' . $e->getMessage());
        }
    }
    
    public function update_proveedor() {
        try {
            $rfc = $_POST['rfc'];
            $id = $_POST['id'];

            $telefono = $_POST['telefono'];
                $telefonoRegex = "/^999\d{7}$/";
            if (!preg_match($telefonoRegex, $telefono)) {
                throw new Exception('El número de teléfono debe tener 10 dígitos y comenzar con 999.');
            }
                // Validar RFC en el servidor también
            if (!preg_match('/^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/', $rfc) || strlen($rfc) !== 13) {
                throw new Exception('El RFC no tiene un formato válido.');
            }

            // Validar que el código postal tenga exactamente 5 dígitos numéricos
            if (!preg_match('/^\d{5}$/', $_POST['codigo_postal'])) {
                throw new Exception('El código postal debe tener exactamente 5 dígitos.');
            }

            // Validar que todos los campos requeridos no estén vacíos
            $requiredFields = ['nombre', 'primer_apellido', 'segundo_apellido', 'telefono', 'rfc', 'calle', 'numero', 'codigo_postal', 'cruzamientos', 'colonia', 'ciudad'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field no puede estar vacío.");
                }
            }

            if ($this->model->rfc_exists($rfc, $id)) {
                throw new Exception('El RFC ya está en uso por otro proveedor.');
            }
    
            $foto = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $fotoTmpPath = $_FILES['foto']['tmp_name'];
                $fotoExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = "proveedor-{$id}-{$_POST['nombre']}-{$_POST['primer_apellido']}.$fotoExtension";
                move_uploaded_file($fotoTmpPath, __DIR__ . "/../uploads/proveedoresimg/$foto");
            } else {
                $foto = $_POST['existing_foto'];
            }
    
            $data = [
                'id' => $id,
                'nombre' => $_POST['nombre'],
                'primer_apellido' => $_POST['primer_apellido'],
                'segundo_apellido' => $_POST['segundo_apellido'],
                'telefono' => $_POST['telefono'],
                'rfc' => $rfc,
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'codigo_postal' => $_POST['codigo_postal'],
                'cruzamientos' => $_POST['cruzamientos'],
                'colonia' => $_POST['colonia'],
                'id_ciudad' => $_POST['ciudad'],
                'foto' => $foto,
                'marcas' => $_POST['marcas']
            ];
    
            $this->model->update_proveedor($data);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log('Update proveedor method failed: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    

    public function toggle_estado_proveedor() {
        if (isset($_POST['id']) && isset($_POST['estado'])) {
            $id = $_POST['id'];
            $estado = $_POST['estado'];
            $result = $this->model->toggle_estado($id, $estado);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID or estado missing']);
        }
    }
    
}

if (isset($_GET['m'])) {
    $method = $_GET['m'];
    $controller = new Adm_Proveedores_controller();
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        $controller->index();
    }
} else {
    $controller = new Adm_Proveedores_controller();
    $controller->index();
}
?>
