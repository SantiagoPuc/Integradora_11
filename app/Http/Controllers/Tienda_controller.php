<?php
require_once(__DIR__ . '/../bd/conexion.php');
require_once(__DIR__ . '/../model/Tienda_model.php');

class Tienda_controller {
    private $model;

    public function __construct() {
        $this->model = new Tienda_model();
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function index() {
        $precioMax = isset($_GET['precio']) && $_GET['precio'] !== '' ? $_GET['precio'] : null;
        $nombre = isset($_GET['nombre']) && $_GET['nombre'] !== '' ? $_GET['nombre'] : '';
    
        $productos = $this->model->getProductosConFiltros($nombre, $precioMax);
    
        include_once(__DIR__ . '/../view_tienda/header.php');
        include_once(__DIR__ . '/../view_tienda/TiendaOnline.php');
        include_once(__DIR__ . '/../view_tienda/footer.php');
    }
    
    public function categoria($categoriaId = null) {
        if ($categoriaId === null) {
            header('Location: ?action=index');
            exit();
        }
    
        $precioMax = isset($_GET['precio']) && $_GET['precio'] !== '' ? $_GET['precio'] : null;
        $nombre = isset($_GET['nombre']) && $_GET['nombre'] !== '' ? $_GET['nombre'] : '';
    
        $productos = $this->model->getProductosPorCategoriaConFiltros($categoriaId, $nombre, $precioMax);
    
        include_once(__DIR__ . '/../view_tienda/header.php');
        include_once(__DIR__ . '/../view_tienda/tienda_categoria_index.php');
        include_once(__DIR__ . '/../view_tienda/footer.php');
    }
    
    public function agregarAlCarrito($codigo) {
        $cantidad = isset($_GET['cantidad']) ? intval($_GET['cantidad']) : 1;
        $producto = $this->model->getProductoPorCodigo($codigo);
        if ($producto) {
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }
            $carrito = $_SESSION['carrito'];
            if (isset($carrito[$codigo])) {
                $nuevaCantidad = $carrito[$codigo]['cantidad'] + $cantidad;
                $carrito[$codigo]['cantidad'] = min($nuevaCantidad, $producto['no_existencias']);
            } else {
                $carrito[$codigo] = [
                    'codigo' => $producto['codigo'],
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'foto' => $producto['foto'],
                    'cantidad' => min($cantidad, $producto['no_existencias']),
                    'no_existencias' => $producto['no_existencias']
                ];
            }
            $_SESSION['carrito'] = $carrito;
    
            if ($this->isAjaxRequest()) {
                echo json_encode(['success' => true]);
                exit();
            } else {
                header('Location: ?action=carrito');
                exit();
            }
        } else {
            if ($this->isAjaxRequest()) {
                echo json_encode(['success' => false]);
                exit();
            } else {
                header('Location: ?action=index');
                exit();
            }
        }
    }
    
    

    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function pagar() {
        if (!isset($_SESSION['client'])) {
            header('Location: ../view/login_registro.php');
            exit();
        }
    
        // Verificar que 'id_cliente' está definido en la sesión del cliente
        if (!isset($_SESSION['client']['id_cliente'])) {
            echo '<script>alert("ID de cliente no encontrado en la sesión."); window.location.href="?action=carrito";</script>';
            exit();
        }
    
        $id_cliente = $_SESSION['client']['id_cliente']; // Obtener el ID del cliente de la sesión
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
    
        if (empty($carrito)) {
            header('Location: ?action=carrito');
            exit();
        }
    
        $productos = [];
        foreach ($carrito as $item) {
            if ($item['cantidad'] > $item['no_existencias']) {
                echo '<script>alert("La cantidad del producto ' . $item['nombre'] . ' no puede exceder las existencias disponibles."); window.location.href="?action=carrito";</script>';
                exit();
            }
            $productos[] = [
                'id_producto' => $item['codigo'],
                'cantidad' => $item['cantidad'],
                'subtotal' => $item['precio'] * $item['cantidad']
            ];
        }
    
        // Imprimir datos para debugging
        error_log('ID Cliente: ' . $id_cliente);
        error_log('Productos: ' . print_r($productos, true));
    
        $result = $this->model->generar_folio_venta($id_cliente, $productos);
    
        if ($result) {
            unset($_SESSION['carrito']); // Vaciar el carrito después de la compra
            header('Location: ?action=compraExitosa');
        } else {
            echo '<script>alert("Hubo un problema al generar la venta."); window.location.href="?action=carrito";</script>';
        }
    }
    

    public function comprarAhora() {
        if (!isset($_SESSION['client'])) {
            header('Location: ../view/login_registro.php');
            exit();
        }
    
        // Verificar que 'id' está definido en la sesión del cliente
        if (!isset($_SESSION['client']['id_cliente'])) {
            echo '<script>alert("ID de cliente no encontrado en la sesión."); window.location.href="?action=carrito";</script>';
            exit();
        }
    
        $id_cliente = $_SESSION['client']['id_cliente']; // Obtener el ID del cliente de la sesión
        $codigo = $_POST['codigo'];
        $cantidad = intval($_POST['cantidad']);
    
        $producto = $this->model->getProductoPorCodigo($codigo);
        if ($cantidad > $producto['no_existencias']) {
            echo '<script>alert("La cantidad no puede exceder las existencias disponibles."); window.location.href="?action=detallesProducto&id='.$codigo.'";</script>';
            exit();
        }
    
        $productos = [
            [
                'id_producto' => $producto['codigo'],
                'cantidad' => $cantidad,
                'subtotal' => $producto['precio'] * $cantidad
            ]
        ];
    
        // Imprimir datos para debugging
        error_log('ID Cliente: ' . $id_cliente);
        error_log('Productos: ' . print_r($productos, true));
    
        $result = $this->model->generar_folio_venta($id_cliente, $productos);
    
        if ($result) {
            header('Location: ?action=compraExitosa');
        } else {
            echo '<script>alert("Hubo un problema al generar la venta."); window.location.href="?action=carrito";</script>';
        }
    }
    
    
    public function misCompras() {
        if (!isset($_SESSION['client'])) {
            header('Location: ../view/login_registro.php');
            exit();
        }
    
        $id_cliente = $_SESSION['client']['id_cliente'];
        $compras = $this->model->getComprasCliente($id_cliente);
    
        include_once(__DIR__ . '/../view_tienda/header.php');
        include_once(__DIR__ . '/../view_tienda/mis_compras.php');
        include_once(__DIR__ . '/../view_tienda/footer.php');
    }
    
    public function detalleCompra($id_venta) {
        if (!isset($_SESSION['client'])) {
            header('Location: ../view/login_registro.php');
            exit();
        }
    
        $detalles = $this->model->getDetalleCompra($id_venta);
        error_log('Detalles de la compra: ' . print_r($detalles, true)); // Agregar un log para depuración
        header('Content-Type: application/json');
        echo json_encode($detalles);
        exit();
    }
    
    
    
    
    
    
    

    public function eliminarDelCarrito($codigo) {
        if (isset($_SESSION['carrito'][$codigo])) {
            unset($_SESSION['carrito'][$codigo]);
        }
        header('Location: ?action=carrito');
    }

    public function actualizarCarrito() {
        $codigo = $_POST['codigo'];
        $cantidad = $_POST['cantidad'];
        if (isset($_SESSION['carrito'][$codigo])) {
            if ($cantidad > 0) {
                $_SESSION['carrito'][$codigo]['cantidad'] = $cantidad;
            } else {
                unset($_SESSION['carrito'][$codigo]);
            }
        }
        header('Location: ?action=carrito');
    }

    public function carrito() {
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        $total = array_reduce($carrito, function($sum, $item) {
            return $sum + ($item['precio'] * $item['cantidad']);
        }, 0);
        include_once(__DIR__ . '/../view_tienda/header_carrito.php');
        include_once(__DIR__ . '/../view_tienda/carrito.php');
        include_once(__DIR__ . '/../view_tienda/footer.php');
    }

    public function buscar() {
        $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
        $categoriaId = isset($_GET['categoria']) ? $_GET['categoria'] : '';
        $precioMax = isset($_GET['precio']) ? $_GET['precio'] : null;

        if ($categoriaId) {
            $productos = $this->model->buscarProductosPorNombreYCategoria($nombre, $categoriaId, $precioMax);
        } else {
            $productos = $this->model->buscarProductosPorNombre($nombre, $precioMax);
        }

        include_once(__DIR__ . '/../view_tienda/header.php');
        include_once(__DIR__ . '/../view_tienda/tienda_categoria_index.php');
        include_once(__DIR__ . '/../view_tienda/footer.php');
    }

    public function masVendidos() {
        $productos = $this->model->getMasVendidos();
        include_once(__DIR__ . '/../view_tienda/header.php');
        include_once(__DIR__ . '/../view_tienda/mas_vendidos.php');
        include_once(__DIR__ . '/../view_tienda/footer.php');
    }

    public function detallesProducto($codigo) {
        $producto = $this->model->getProductoPorCodigo($codigo);
        header('Content-Type: application/json');
        echo json_encode($producto);
        exit();
    }
}
?>
