<?php 
require_once(__DIR__ . '/../bd/conexion.php');

class Compra_model {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function generar_folio_compra($id_usuario, $id_proveedor, $productos) {
        try {
            $this->db->beginTransaction();

            // Insertar la compra
            $stmt = $this->db->prepare("INSERT INTO Compra2 (id_usuario, id_proveedor, Fecha_hora) VALUES (:id_usuario, :id_proveedor, NOW())");
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_proveedor', $id_proveedor);
            $stmt->execute();

            // Obtener el ID de la compra insertada
            $id_compra = $this->db->lastInsertId();

            // Insertar los productos de la compra y actualizar existencias
            foreach ($productos as $producto) {
                // Insertar producto
                $stmt = $this->db->prepare("INSERT INTO Producto_Compra3 (id_compra, Codigo_producto, Cantidad, Subtotal) VALUES (:id_compra, :Codigo_producto, :Cantidad, :Subtotal)");
                $stmt->bindParam(':id_compra', $id_compra);
                $stmt->bindParam(':Codigo_producto', $producto['id_producto']);
                $stmt->bindParam(':Cantidad', $producto['cantidad']);
                $stmt->bindParam(':Subtotal', $producto['subtotal']);
                $stmt->execute();

                // Actualizar existencias del producto
                $stmt = $this->db->prepare("UPDATE Producto SET No_existencias = No_existencias + :cantidad WHERE Codigo = :id_producto");
                $stmt->bindParam(':cantidad', $producto['cantidad']);
                $stmt->bindParam(':id_producto', $producto['id_producto']);
                $stmt->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error en generar_folio_compra: ' . $e->getMessage());
            return false;
        }
    }

    public function buscarComprasPorFolio($folio) {
        $sql = "SELECT C.ID_compra AS 'Folio de Compra', U.Nombre AS 'Nombre del Usuario que registro la compra', 
                       P.Nombre AS 'Nombre del Proveedor', C.Fecha_hora AS 'Fecha y Hora', 
                       SUM(PC.Subtotal) AS 'Monto Total' 
                FROM Compra2 C 
                INNER JOIN Usuario U ON C.id_usuario = U.ID 
                INNER JOIN Proveedor P ON C.id_proveedor = P.ID_proveedor 
                LEFT JOIN Producto_Compra3 PC ON C.ID_compra = PC.id_compra 
                WHERE C.ID_compra LIKE :folio
                GROUP BY C.ID_compra, U.Nombre, P.Nombre, C.Fecha_hora";
        $stmt = $this->db->prepare($sql);
        $folio = "%$folio%";
        $stmt->bindParam(':folio', $folio);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_sugerencias_folio($query) {
        $sql = "SELECT ID_compra FROM Compra2 WHERE ID_compra LIKE :query LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $query = "%$query%";
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function obtener_todas_las_compras() {
        $sql = "SELECT C.ID_compra AS 'Folio de Compra', U.Nombre AS 'Nombre del Usuario que registro la compra', P.Nombre AS 'Nombre del Proveedor', 
                C.Fecha_hora AS 'Fecha y Hora', SUM(PC.Subtotal) AS 'Monto Total' 
                FROM Compra2 C 
                INNER JOIN Usuario U ON C.id_usuario = U.ID 
                INNER JOIN Proveedor P ON C.id_proveedor = P.ID_proveedor 
                LEFT JOIN Producto_Compra3 PC ON C.ID_compra = PC.id_compra 
                GROUP BY C.ID_compra, U.Nombre, P.Nombre, C.Fecha_hora";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_todos_los_detalles_de_compras() {
        $sql = "SELECT PC.id_compra AS 'Folio de Compra', PC.Codigo_producto AS 'Código del Producto', P.Nombre AS 'Nombre del Producto', PC.Cantidad, PC.Subtotal 
                FROM Producto_Compra3 PC 
                INNER JOIN Producto P ON PC.Codigo_producto = P.Codigo";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_id_compra() {
        $stmt = $this->db->query("SELECT MAX(ID_compra) AS ID FROM Compra2");
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
        $stmt = $this->db->prepare("UPDATE Producto SET No_existencias = No_existencias + :cantidad WHERE Codigo = :id_producto");
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
    }

    public function mostrar_informacion_compra($id_compra = null) {
        $sql = "SELECT C.ID_compra AS 'Folio de Compra', U.Nombre AS 'Nombre del Usuario que registro la compra', P.Nombre AS 'Nombre del Proveedor', 
                C.Fecha_hora AS 'Fecha y Hora', SUM(PC.Subtotal) AS 'Monto Total' 
                FROM Compra2 C 
                INNER JOIN Usuario U ON C.id_usuario = U.ID 
                INNER JOIN Proveedor P ON C.id_proveedor = P.ID_proveedor 
                LEFT JOIN Producto_Compra3 PC ON C.ID_compra = PC.id_compra ";
        
        if ($id_compra) {
            $sql .= "WHERE C.ID_compra = :id_compra ";
        }

        $sql .= "GROUP BY C.ID_compra, U.Nombre, P.Nombre, C.Fecha_hora";

        $stmt = $this->db->prepare($sql);
        if ($id_compra) {
            $stmt->bindParam(':id_compra', $id_compra);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mostrar_detalle_compra($id_compra = null) {
        $sql = "SELECT PC.id_compra AS 'Folio de Compra', PC.Codigo_producto AS 'Código del Producto', P.Nombre AS 'Nombre del Producto', PC.Cantidad, PC.Subtotal 
                FROM Producto_Compra3 PC 
                INNER JOIN Producto P ON PC.Codigo_producto = P.Codigo ";
        
        if ($id_compra) {
            $sql .= "WHERE PC.id_compra = :id_compra";
        }

        $stmt = $this->db->prepare($sql);
        if ($id_compra) {
            $stmt->bindParam(':id_compra', $id_compra);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar_proveedor($query) {
        try {
            $sql = "SELECT ID_proveedor, Nombre, Apellido_1, Apellido_2, RFC, Telefono 
                    FROM Proveedor 
                    WHERE ID_proveedor LIKE :query OR Nombre LIKE :query OR Apellido_1 LIKE :query OR Apellido_2 LIKE :query";
            $stmt = $this->db->prepare($sql);
            $query = "%$query%";
            $stmt->bindParam(':query', $query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en buscar_proveedor: ' . $e->getMessage());
            return false;
        }
    }
    

    public function buscar_producto($query) {
        try {
            $sql = "SELECT Codigo, Nombre, Precio_compra, No_existencias 
                    FROM Producto 
                    WHERE Codigo LIKE :query OR Nombre LIKE :query";
            $stmt = $this->db->prepare($sql);
            $query = "%$query%";
            $stmt->bindParam(':query', $query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en buscar_producto: ' . $e->getMessage());
            return false;
        }
    }
    
}
?>
