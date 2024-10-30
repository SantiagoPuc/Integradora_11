<?php
require_once(__DIR__ . '/../bd/conexion.php');

class Venta_model {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }
    
    
    public function generar_folio_venta($id_usuario, $id_cliente, $productos) {
        try {
            $this->db->beginTransaction();

            // Insertar la venta
            $stmt = $this->db->prepare("INSERT INTO Venta (id_usuario, id_cliente, Fecha_hora) VALUES (:id_usuario, :id_cliente, NOW())");
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->execute();

            // Obtener el ID de la venta insertada
            $id_venta = $this->db->lastInsertId();

            // Insertar los productos de la venta y actualizar existencias
            foreach ($productos as $producto) {
                // Insertar producto
                $stmt = $this->db->prepare("INSERT INTO Producto_Venta (id_venta, Codigo_producto, Cantidad, Subtotal) VALUES (:id_venta, :Codigo_producto, :Cantidad, :Subtotal)");
                $stmt->bindParam(':id_venta', $id_venta);
                $stmt->bindParam(':Codigo_producto', $producto['id_producto']);
                $stmt->bindParam(':Cantidad', $producto['cantidad']);
                $stmt->bindParam(':Subtotal', $producto['subtotal']);
                $stmt->execute();

                // Actualizar existencias del producto
                $stmt = $this->db->prepare("UPDATE Producto SET No_existencias = No_existencias - :cantidad WHERE Codigo = :id_producto");
                $stmt->bindParam(':cantidad', $producto['cantidad']);
                $stmt->bindParam(':id_producto', $producto['id_producto']);
                $stmt->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error en generar_folio_venta: ' . $e->getMessage());
            return false;
        }
    }

    
    
    
    public function buscarVentasPorFolio($folio) {
        $sql = "SELECT V.ID_venta AS 'Folio de Venta', U.Nombre AS 'Nombre del Usuario que registro la venta', 
                       C.Nombre AS 'Nombre del Cliente', V.Fecha_hora AS 'Fecha y Hora', 
                       SUM(PV.Subtotal) AS 'Monto Total' 
                FROM Venta V 
                INNER JOIN Usuario U ON V.id_usuario = U.ID 
                INNER JOIN Cliente C ON V.id_cliente = C.ID_cliente 
                LEFT JOIN Producto_Venta PV ON V.ID_venta = PV.id_venta 
                WHERE V.ID_venta LIKE :folio
                GROUP BY V.ID_venta, U.Nombre, C.Nombre, V.Fecha_hora";
        $stmt = $this->db->prepare($sql);
        $folio = "%$folio%";
        $stmt->bindParam(':folio', $folio);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtener_sugerencias_folio($query) {
        $sql = "SELECT ID_venta FROM Venta WHERE ID_venta LIKE :query LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $query = "%$query%";
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function obtener_todas_las_ventas() {
        $sql = "SELECT V.ID_venta AS 'Folio de Venta', U.Nombre AS 'Nombre del Usuario que registro la venta', C.Nombre AS 'Nombre del Cliente', 
                V.Fecha_hora AS 'Fecha y Hora', SUM(PV.Subtotal) AS 'Monto Total' 
                FROM Venta V 
                INNER JOIN Usuario U ON V.id_usuario = U.ID 
                INNER JOIN Cliente C ON V.id_cliente = C.ID_cliente 
                LEFT JOIN Producto_Venta PV ON V.ID_venta = PV.id_venta 
                GROUP BY V.ID_venta, U.Nombre, C.Nombre, V.Fecha_hora";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_todos_los_detalles_de_ventas() {
        $sql = "SELECT PV.id_venta AS 'Folio de Venta', PV.Codigo_producto AS 'Código del Producto', P.Nombre AS 'Nombre del Producto', PV.Cantidad, PV.Subtotal 
                FROM Producto_Venta PV 
                INNER JOIN Producto P ON PV.Codigo_producto = P.Codigo";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_id_venta() {
        $stmt = $this->db->query("SELECT MAX(ID_venta) AS ID FROM Venta");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['ID'];
    }

    public function obtener_no_existencias($id_producto) {
        $stmt = $this->db->prepare("SELECT No_existencias FROM Producto WHERE Codigo = :id_producto");
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['No_existencias'];
    }

    public function actualizar_existencias_producto($id_producto, $cantidad) {
        $stmt = $this->db->prepare("UPDATE Producto SET No_existencias = No_existencias - :cantidad WHERE Codigo = :id_producto");
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
    }

    public function mostrar_informacion_venta($id_venta = null) {
        $sql = "SELECT V.ID_venta AS 'Folio de Venta', U.Nombre AS 'Nombre del Usuario que registro la venta', C.Nombre AS 'Nombre del Cliente', 
                V.Fecha_hora AS 'Fecha y Hora', SUM(PV.Subtotal) AS 'Monto Total' 
                FROM Venta V 
                INNER JOIN Usuario U ON V.id_usuario = U.ID 
                INNER JOIN Cliente C ON V.id_cliente = C.ID_cliente 
                LEFT JOIN Producto_Venta PV ON V.ID_venta = PV.id_venta ";
        
        if ($id_venta) {
            $sql .= "WHERE V.ID_venta = :id_venta ";
        }

        $sql .= "GROUP BY V.ID_venta, U.Nombre, C.Nombre, V.Fecha_hora";

        $stmt = $this->db->prepare($sql);
        if ($id_venta) {
            $stmt->bindParam(':id_venta', $id_venta);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mostrar_detalle_venta($id_venta = null) {
        $sql = "SELECT PV.id_venta AS 'Folio de Venta', PV.Codigo_producto AS 'Código del Producto', P.Nombre AS 'Nombre del Producto', PV.Cantidad, PV.Subtotal 
                FROM Producto_Venta PV 
                INNER JOIN Producto P ON PV.Codigo_producto = P.Codigo ";
        
        if ($id_venta) {
            $sql .= "WHERE PV.id_venta = :id_venta";
        }

        $stmt = $this->db->prepare($sql);
        if ($id_venta) {
            $stmt->bindParam(':id_venta', $id_venta);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar_cliente($query) {
        $sql = "SELECT ID_cliente, Nombre, Apellido_1, Apellido_2, Correo, Telefono 
                FROM Cliente 
                WHERE ID_cliente LIKE :query OR Nombre LIKE :query OR Apellido_1 LIKE :query OR Apellido_2 LIKE :query";
        $stmt = $this->db->prepare($sql);
        $query = "%$query%";
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar_producto($query) {
        $sql = "SELECT Codigo, Nombre, Precio, No_existencias 
                FROM Producto 
                WHERE Codigo LIKE :query OR Nombre LIKE :query";
        $stmt = $this->db->prepare($sql);
        $query = "%$query%";
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
