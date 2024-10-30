<?php
    require_once(__DIR__ . '/../model/Adm_Productos_model.php');

    class Adm_Productos_controller{
        private $model;

        public function __construct()
        {
            $this->model = new Adm_Productos_model();
            
        }

        public function index(){
            $productos = $this->model->get();
            include_once(__DIR__ . '/../view/header.php');
            include_once(__DIR__ . '/../view/Adm_Productos.php');
            include_once(__DIR__ . '/../view/footer.php');
        }

        public function index_emp(){
            $productos = $this->model->get();
            include_once(__DIR__ . '/../view/header_emp.php');
            include_once(__DIR__ . '/../view/Adm_Productos.php');
            include_once(__DIR__ . '/../view/footer.php');
        }

        public function create() {
            try {
                $foto = null;
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                    $fotoTmpPath = $_FILES['foto']['tmp_name'];
                    $fotoExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    $foto = "{$_POST['nombre']}-{$_POST['modelo']}.$fotoExtension";
                    move_uploaded_file($fotoTmpPath, __DIR__ . "/../uploads/productosimg/$foto");
                }
                else{
                    $foto = 'producto.jpg';
                }
        
                $requiredFields = ['no_serie', 'nombre', 'precio', 'modelo', 'descripcion', 'id_marca', 'id_categoria', 'precio_compra'];
                foreach ($requiredFields as $field) {
                    if (!isset($_POST[$field]) || empty($_POST[$field])) {
                        throw new Exception("El campo $field es obligatorio y no puede estar vacío.");
                    }
                }
        
                $data = [
                    'no_serie' => $_POST['no_serie'],
                    'nombre' => $_POST['nombre'],
                    'precio' => $_POST['precio'],
                    'modelo' => $_POST['modelo'],
                    'descripcion' => $_POST['descripcion'],
                    'id_marca' => $_POST['id_marca'],
                    'id_categoria' => $_POST['id_categoria'],
                    'precio_compra' => $_POST['precio_compra'],
                    'foto' => $foto,
                    'no_existencias' => 0
                ];
        
                error_log('Datos para insertar: ' . print_r($data, true));
        
                $this->model->create($data);
        
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                error_log('Create method failed: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }        
        
        
    
        public function get_producto() {
            if (isset($_GET['codigo'])) {
                $codigo = $_GET['codigo'];
                $producto = $this->model->get_id($codigo);
                if ($producto) {
                    echo json_encode($producto);
                } else {
                    echo json_encode(['error' => 'Producto no encontrado']);
                }
            } else {
                echo json_encode(['error' => 'Código no proporcionado']);
            }
        }

        public function get_producto2() {
            if (isset($_GET['codigo'])) {
                $codigo = $_GET['codigo'];
                $producto = $this->model->get_producto2($codigo);
                if ($producto) {
                    echo json_encode($producto);
                } else {
                    echo json_encode(['error' => 'Producto no encontrado']);
                }
            } else {
                echo json_encode(['error' => 'Código no proporcionado']);
            }
        }
        
        public function update() {
            try {
                $foto = null;
        
                // Manejo de la foto
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                    $fotoTmpPath = $_FILES['foto']['tmp_name'];
                    $fotoExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    $foto = "{$_POST['nombre']}-{$_POST['modelo']}.$fotoExtension";
                    move_uploaded_file($fotoTmpPath, __DIR__ . "/../uploads/productosimg/$foto");
                } else {
                    // Usar la foto existente si no se ha cargado una nueva
                    $foto = $_POST['existing_foto'];
                }
        
                $data = [
                    'codigo' => $_POST['codigo'],
                    'no_serie' => $_POST['no_serie'],
                    'nombre' => $_POST['nombre'],
                    'precio' => $_POST['precio'],
                    'modelo' => $_POST['modelo'],
                    'descripcion' => $_POST['descripcion'],
                    'id_marca' => $_POST['id_marca'],
                    'id_categoria' => $_POST['id_categoria'],
                    'precio_compra' => $_POST['precio_compra'],
                    'foto' => $foto
                ];
        
                $this->model->update($data);
        
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                error_log('Update method failed: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        
    
        private function uploadFoto() {
            $upload_dir = '../img/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $foto = $upload_dir . basename($_FILES['foto']['name']);
            move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
            return $foto;
        }
    
        public function delete() {
            try {
                if (isset($_POST['codigo'])) {
                    $codigo = $_POST['codigo'];
                    if ($this->model->delete($codigo)) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Código no proporcionado']);
                }
            } catch (Exception $e) {
                error_log('Delete method failed: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Delete method failed: ' . $e->getMessage()]);
            }
        }

        public function search() {
            if (isset($_GET['query'])) {
                $query = $_GET['query'];
                $productos = $this->model->search($query);
                echo json_encode($productos);
            } else {
                echo json_encode([]);
            }
        }
        
        
    }
    //meee
?>