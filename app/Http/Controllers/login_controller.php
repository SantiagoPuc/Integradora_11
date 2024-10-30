<?php
require_once(__DIR__ . '/../model/Adm_Usuarios_model.php');
require_once(__DIR__ . '/../model/Adm_Clientes_model.php');
require_once(__DIR__ . '/../model/login_model.php');

class Auth_Controller {
    private $usuariosModel;
    private $clientesModel;
    private $loginModel;

    public function __construct() {
        $this->usuariosModel = new Adm_Usuarios_model();
        $this->clientesModel = new Adm_Clientes_model();
        $this->loginModel = new Login_Model();
    }

    public function index(){
        include_once(__DIR__ . '/../view/login_registro.php');

    }

    public function login() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', 1440);
            session_set_cookie_params(1440);
            session_start();
        }
        $username = $_POST['usuario'];
        $password = $_POST['contraseña'];

        // Verificar en la tabla de usuarios
        $user = $this->usuariosModel->authenticate($username, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            if ($user['id_tipo'] == 1) {
                header('Location: ../view/Adm_index.php');
            } else if ($user['id_tipo'] == 2) {
                header('Location: ../view/Emp_index_productos.php');
            }
            exit();
        }

        // Verificar en la tabla de clientes
        $client = $this->clientesModel->authenticate($username, $password);
        if ($client) {
            $_SESSION['client'] = $client;
            header('Location: ../view_tienda/Tienda_index.php');
            exit();
        }

        // Si no se encuentra el usuario o cliente
        header('Location: ../view/login_registro.php?error=invalid_credentials');
        exit();
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ../view/login_registro.php');
        exit();
    }
    
    public function register() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', 1440);
            session_set_cookie_params(1440);
            session_start();
        }

        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $fotoTmpPath = $_FILES['foto']['tmp_name'];
            $fotoExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto = "{$_POST['id']}-{$_POST['nombre']}-{$_POST['primer_apellido']}.$fotoExtension";
            move_uploaded_file($fotoTmpPath, __DIR__ . "/../uploads/$foto");
        }
        else{
            $foto = '0.jpg';
        }

        $id = $_POST['id'];
        $correo = $_POST['correo'];

        // Validar si el ID o el correo ya están registrados
        if ($this->loginModel->idExists($id)) {
            header('Location: ../view/login_registro.php?error=id_exists');
            exit();
        }

        if ($this->loginModel->emailExists($correo)) {
            header('Location: ../view/login_registro.php?error=email_exists');
            exit();
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
            'foto' => $foto,
            'passwordd_C' => $_POST['passwordd_C']
        ];

        $result = $this->loginModel->add_cliente_login($data);

        if ($result) {
            // Autenticación automática después de registro exitoso
            $client = $this->clientesModel->authenticate($data['correo'], $data['passwordd_C']);
            if ($client) {
                $_SESSION['client'] = $client;
                header('Location: ../view_tienda/Tienda_index.php');
                exit();
            }
        } else {
            header('Location: ../view/login_registro.php?error=register_failed');
            exit();
        }
    }

    public function recuperar_contraseña() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', 1440);
            session_set_cookie_params(1440);
            session_start();
        }
    
        $correo = $_POST['recuperar_correo'];
        $nueva_contraseña = $_POST['nueva_contraseña'];
        $confirmar_nueva_contraseña = $_POST['confirmar_nueva_contraseña'];
    
        if ($nueva_contraseña !== $confirmar_nueva_contraseña) {
            header('Location: ../view/login_registro.php?error=password_mismatch');
            exit();
        }
    
        // Verificar si el correo existe y la contraseña es nula
        $client = $this->loginModel->get_cliente_by_email($correo);
        if ($client && empty($client['passwordd_C'])) {
            // Actualizar la contraseña
            $result = $this->loginModel->update_password($correo, $nueva_contraseña);
            if ($result) {
                // Autenticación automática después de actualizar la contraseña
                $client = $this->clientesModel->authenticate($correo, $nueva_contraseña);
                if ($client) {
                    $_SESSION['client'] = $client;
                    header('Location: ../view_tienda/Tienda_index.php');
                    exit();
                }
            }
        }
    
        // Si no se encuentra el cliente o la contraseña no es nula
        header('Location: ../view/login_registro.php?error=invalid_recuperar');
        exit();
    }
    

    public function getEstadosByPais() {
        $idPais = $_GET['idPais'];
        $estados = $this->loginModel->getEstadosByPais($idPais);
        echo json_encode($estados);
    }

    public function getCiudadesByEstado() {
        $idEstado = $_GET['idEstado'];
        $ciudades = $this->loginModel->getCiudadesByEstado($idEstado);
        echo json_encode($ciudades);
    }

    public function getPaises() {
        $paises = $this->loginModel->get_paises();
        echo json_encode($paises);
    }
}

// En tu archivo de rutas o de controlador principal
$controller = new Auth_Controller();

if (!empty($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        // Manejar acción inválida
        echo "Acción inválida.";
    }
} else {
    echo "Acción no proporcionada.";
}

?>
