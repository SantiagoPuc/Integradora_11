<?php
require_once(__DIR__ . '/../model/Adm_Clientes_model.php');

// Habilita el logging de errores y deshabilita la salida de errores
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');
ini_set('display_errors', 0);

if (isset($_GET['action'])) {
    header('Content-Type: application/json'); // Asegúrate de que el encabezado Content-Type es JSON
    $action = $_GET['action'];
    switch ($action) {
        case 'get_estados':
            getEstados();
            break;
        case 'get_ciudades':
            getCiudades();
            break;
        case 'get_cliente':
            getCliente();
            break;
        case 'get_cliente2':
            getCliente2();
            break;
        case 'get_paises':
            get_paises();
            break;
        case 'get_cliente_full':
            getClienteFull();
            break;
        case 'update_cliente':
            $controller = new Adm_Clientes_controller();
            $controller->updateCliente(); // Asegúrate de que esta línea existe
            break;
        case 'delete_cliente':
            deleteCliente();
            break;
        case 'buscar_cliente':
            buscarCliente();
            break;
        default:
            echo json_encode(["error" => "Invalid action"]);
            break;
    }
    exit; // Asegúrate de que el script se detiene aquí
}

function getEstados() {
    if (isset($_GET['id_pais'])) {
        $idPais = $_GET['id_pais'];
        $model = new Adm_Clientes_model();
        $estados = $model->getEstadosByPais($idPais);
        echo json_encode($estados);
    } else {
        echo json_encode(["error" => "Missing id_pais parameter"]);
    }
}

function getCiudades() {
    if (isset($_GET['id_estado'])) {
        $idEstado = $_GET['id_estado'];
        $model = new Adm_Clientes_model();
        $ciudades = $model->getCiudadesByEstado($idEstado);
        echo json_encode($ciudades);
    } else {
        echo json_encode(["error" => "Missing id_estado parameter"]);
    }
}

function getCliente() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $model = new Adm_Clientes_model();
        $cliente = $model->get_cliente($id);
        echo json_encode($cliente);
    } else {
        echo json_encode(["error" => "Missing id parameter"]);
    }
}
function getCliente2() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $model = new Adm_Clientes_model();
        $cliente = $model->get_cliente2($id);
        echo json_encode($cliente);
    } else {
        echo json_encode(["error" => "Missing id parameter"]);
    }
}

function getClienteFull() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $model = new Adm_Clientes_model();
        $cliente = $model->get_cliente_full($id);
        echo json_encode($cliente);
    } else {
        echo json_encode(["error" => "Missing id parameter"]);
    }
}

function get_paises() {
    $model = new Adm_Clientes_model();
    $paises = $model->get_paises();
    if (is_array($paises)) {
        echo json_encode($paises);
    } else {
        echo json_encode([]);
    }
}

function deleteCliente() {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $model = new Adm_Clientes_model();
        try {
            $success = $model->delete_cliente($id);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            error_log('Delete cliente method failed: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del cliente no proporcionado.']);
    }
}


function buscarCliente() {
    if (isset($_GET['q']) || isset($_GET['id'])) {
        $query = isset($_GET['q']) ? $_GET['q'] : $_GET['id'];
        $model = new Adm_Clientes_model();
        $clientes = $model->buscar_cliente($query);
        echo json_encode($clientes);
    } else {
        echo json_encode(["error" => "Missing query parameter"]);
    }
}

class Adm_Clientes_controller {
    private $model;

    public function __construct() {
        $this->model = new Adm_Clientes_model();
    }

    public function index() {
        try {
            $clientes = $this->model->get();
            $paises = $this->model->get_paises();
            
            include_once(__DIR__ . '/../view/header.php');
            include_once(__DIR__ . '/../view/Adm_Clientes.php');
            include_once(__DIR__ . '/../view/footer.php');
        } catch (Exception $e) {
            error_log('Index method failed: ' . $e->getMessage());
            die('Index method failed: ' . $e->getMessage());
        }
    }

    public function index_emp() {
        try {
            $clientes = $this->model->get();
            $paises = $this->model->get_paises();
            
            include_once(__DIR__ . '/../view/header_emp.php');
            include_once(__DIR__ . '/../view/Adm_Clientes.php');
            include_once(__DIR__ . '/../view/footer.php');
        } catch (Exception $e) {
            error_log('Index method failed: ' . $e->getMessage());
            die('Index method failed: ' . $e->getMessage());
        }
    }

    public function add_cliente() {
        try {
            if (!isset($_POST['telefono']) || !isset($_POST['codigo_postal']) || !isset($_POST['ciudad'])) {
                throw new Exception("Teléfono, código postal y ciudad son requeridos.");
            }
    
            $id = $this->calcular_id_cliente($_POST['telefono'], $_POST['codigo_postal']);
            $foto = null;
    
            // Manejo de la foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $fotoTmpPath = $_FILES['foto']['tmp_name'];
                $fotoExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = "{$id}-{$_POST['nombre']}-{$_POST['primer_apellido']}.$fotoExtension";
                move_uploaded_file($fotoTmpPath, __DIR__ . "/../uploads/$foto");
            }
            else{
                $foto = '0.jpg';
            }
    
            $data = [
                'id' => $id,
                'nombre' => $_POST['nombre'],
                'primer_apellido' => $_POST['primer_apellido'],
                'segundo_apellido' => $_POST['segundo_apellido'],
                'telefono' => $_POST['telefono'],
                'correo' => $_POST['correo'],
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'codigo_postal' => $_POST['codigo_postal'],
                'cruzamientos' => $_POST['cruzamientos'],
                'colonia' => $_POST['colonia'],
                'id_ciudad' => $_POST['ciudad'],
                'foto' => $foto
            ];
            $this->model->add_cliente($data);
            header("Location: Adm_index_Clientes.php");
        } catch (Exception $e) {
            error_log('Add cliente method failed: ' . $e->getMessage());
            die('Add cliente method failed: ' . $e->getMessage());
        }
    }

    public function add_cliente_login() {
        try {
            if (!isset($_POST['telefono']) || !isset($_POST['codigo_postal']) || !isset($_POST['ciudad'])) {
                throw new Exception("Teléfono, código postal y ciudad son requeridos.");
            }
    
            $id = $this->calcular_id_cliente($_POST['telefono'], $_POST['codigo_postal']);
            $foto = null;
    
            // Manejo de la foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $fotoTmpPath = $_FILES['foto']['tmp_name'];
                $fotoExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = "{$id}-{$_POST['nombre']}-{$_POST['primer_apellido']}.$fotoExtension";
                move_uploaded_file($fotoTmpPath, __DIR__ . "/../uploads/$foto");
            }
            else{
                $foto = '0.jpg';
            }
    
            $data = [
                'id' => $id,
                'nombre' => $_POST['nombre'],
                'primer_apellido' => $_POST['primer_apellido'],
                'segundo_apellido' => $_POST['segundo_apellido'],
                'telefono' => $_POST['telefono'],
                'correo' => $_POST['correo'],
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'codigo_postal' => $_POST['codigo_postal'],
                'cruzamientos' => $_POST['cruzamientos'],
                'colonia' => $_POST['colonia'],
                'id_ciudad' => $_POST['ciudad'],
                'foto' => $foto,
                'passwordd_C' => $_POST['passwordd_C']
            ];
            $this->model->add_cliente_login($data);
            header("Location: Adm_index_Clientes.php");
        } catch (Exception $e) {
            error_log('Add cliente method failed: ' . $e->getMessage());
            die('Add cliente method failed: ' . $e->getMessage());
        }
    }
    

    private function calcular_id_cliente($telefono, $codigo_postal) {
        if (is_numeric($telefono) && is_numeric($codigo_postal)) {
            $ultimo_cinco_telefono = substr($telefono, -5);
            $hipotenusa = sqrt(pow((int)$ultimo_cinco_telefono, 2) + pow((int)$codigo_postal, 2));
            return intval($hipotenusa); // Hipotenusa completa
        } else {
            throw new Exception("Teléfono o código postal no son numéricos.");
        }
    }
    
    public function updateCliente() {
        try {
            $foto = null;
    
            // Manejo de la foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $fotoTmpPath = $_FILES['foto']['tmp_name'];
                $fotoExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = "{$_POST['id']}-{$_POST['nombre']}-{$_POST['primer_apellido']}.$fotoExtension";
                move_uploaded_file($fotoTmpPath, __DIR__ . "/../uploads/$foto");
            } else {
                // Usar la foto existente si no se ha cargado una nueva
                $foto = $_POST['existing_foto'];
            }
    
            $data = [
                'id' => $_POST['id'],
                'nombre' => $_POST['nombre'],
                'primer_apellido' => $_POST['primer_apellido'],
                'segundo_apellido' => $_POST['segundo_apellido'],
                'telefono' => $_POST['telefono'],
                'correo' => $_POST['correo'],
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'codigo_postal' => $_POST['codigo_postal'],
                'cruzamientos' => $_POST['cruzamientos'],
                'colonia' => $_POST['colonia'],
                'id_ciudad' => $_POST['ciudad'],
                'foto' => $foto
            ];
    
            error_log('Datos recibidos para actualizar: ' . print_r($data, true));
    
            $updated = $this->model->update_cliente($data);
            if ($updated) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Error al actualizar el cliente en la base de datos.');
            }
        } catch (Exception $e) {
            error_log('Update cliente method failed: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    
    
    
}

if (isset($_GET['m'])) {
    $method = $_GET['m'];
    $controller = new Adm_Clientes_controller();
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        $controller->index();
    }
} else {
    $controller = new Adm_Clientes_controller();
    $controller->index();
}
?>
