<?php
class Tienda_model {
    private $DB;

    public function __construct() {
        $this->DB = Database::connect();
    }

    public function getProductos($precioMax = null) {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, 
                Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, 
                Producto.Descripcion AS descripcion, Producto.No_existencias AS no_existencias, 
                Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
                FROM Producto 
                LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
                INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
                WHERE Producto.No_existencias >= 1";
        if ($precioMax !== null) {
            $sql .= " AND Producto.Precio <= ?";
            $stmt = $this->DB->prepare($sql);
            $stmt->execute([$precioMax]);
        } else {
            $stmt = $this->DB->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getComprasCliente($id_cliente) {
        $sql = "SELECT v.id_venta AS id_venta, v.Fecha_hora AS fecha, SUM(pv.Subtotal) AS total
                FROM Venta v
                INNER JOIN Producto_Venta pv ON v.id_venta = pv.id_venta
                WHERE v.id_cliente = ?
                GROUP BY v.id_venta
                ORDER BY v.Fecha_hora DESC";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getDetalleCompra($id_venta) {
        $sql = "SELECT pv.Codigo_producto AS codigo, p.Nombre AS nombre, p.Modelo AS modelo, pv.Cantidad AS cantidad, pv.Subtotal AS subtotal
                FROM Producto_Venta pv
                INNER JOIN Producto p ON pv.Codigo_producto = p.Codigo
                WHERE pv.id_venta = ?";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute([$id_venta]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Asegurarse de que 'subtotal' sea un número
        foreach ($result as &$detalle) {
            $detalle['subtotal'] = (float) $detalle['subtotal'];
        }
        return $result;
    }
    
    
    
    

    public function getProductosPorCategoria($categoriaId, $precioMax = null) {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, 
                Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, 
                Producto.Descripcion AS descripcion, Producto.No_existencias AS no_existencias, 
                Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
                FROM Producto 
                LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
                INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
                WHERE Producto.id_categoria = ? AND Producto.No_existencias >= 1";
        if ($precioMax !== null) {
            $sql .= " AND Producto.Precio <= ?";
            $stmt = $this->DB->prepare($sql);
            $stmt->execute([$categoriaId, $precioMax]);
        } else {
            $stmt = $this->DB->prepare($sql);
            $stmt->execute([$categoriaId]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarProductosPorNombre($nombre, $precioMax = null) {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, 
                Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, 
                Producto.Descripcion AS descripcion, Producto.No_existencias AS no_existencias, 
                Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
                FROM Producto 
                LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
                INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
                WHERE Producto.Nombre LIKE ? AND Producto.No_existencias >= 1";
        if ($precioMax !== null) {
            $sql .= " AND Producto.Precio <= ?";
            $stmt = $this->DB->prepare($sql);
            $stmt->execute(['%' . $nombre . '%', $precioMax]);
        } else {
            $stmt = $this->DB->prepare($sql);
            $stmt->execute(['%' . $nombre . '%']);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMasVendidos($limite = 10) {
        $sql = "SELECT p.Codigo AS codigo, p.No_serie AS no_serie, p.Nombre AS nombre, p.Precio AS precio, 
                p.Precio_compra AS precio_compra, p.Modelo AS modelo, p.Descripcion AS descripcion, 
                p.No_existencias AS no_existencias, p.Foto AS foto, 
                SUM(pv.cantidad) AS total_vendido
                FROM Producto p
                INNER JOIN producto_venta pv ON p.Codigo = pv.codigo_producto
                WHERE p.No_existencias >= 1
                GROUP BY p.Codigo
                ORDER BY total_vendido DESC
                LIMIT ?";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindValue(1, (int)$limite, PDO::PARAM_INT); // Aquí se asegura de que el límite sea un entero
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductosConFiltros($nombre = '', $precioMax = null) {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, 
                Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, 
                Producto.Descripcion AS descripcion, Producto.No_existencias AS no_existencias, 
                Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
                FROM Producto 
                LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
                INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
                WHERE Producto.No_existencias >= 1";
    
        $params = [];
    
        if ($nombre) {
            $sql .= " AND Producto.Nombre LIKE ?";
            $params[] = '%' . $nombre . '%';
        }
    
        if ($precioMax !== null) {
            $sql .= " AND Producto.Precio <= ?";
            $params[] = $precioMax;
        }
    
        $stmt = $this->DB->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductosPorCategoriaConFiltros($categoriaId, $nombre = '', $precioMax = null) {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, 
                Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, 
                Producto.Descripcion AS descripcion, Producto.No_existencias AS no_existencias, 
                Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
                FROM Producto 
                LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
                INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
                WHERE Producto.id_categoria = ? AND Producto.No_existencias >= 1";
        
        $params = [$categoriaId];
        
        if ($nombre) {
            $sql .= " AND Producto.Nombre LIKE ?";
            $params[] = '%' . $nombre . '%';
        }
    
        if ($precioMax !== null) {
            $sql .= " AND Producto.Precio <= ?";
            $params[] = $precioMax;
        }
    
        $stmt = $this->DB->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarProductosPorNombreYCategoria($nombre, $categoriaId, $precioMax = null) {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, 
                Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, 
                Producto.Descripcion AS descripcion, Producto.No_existencias AS no_existencias, 
                Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
                FROM Producto 
                LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
                INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
                WHERE Producto.Nombre LIKE ? AND Producto.id_categoria = ? AND Producto.No_existencias >= 1";
        if ($precioMax !== null) {
            $sql .= " AND Producto.Precio <= ?";
            $stmt = $this->DB->prepare($sql);
            $stmt->execute(['%' . $nombre . '%', $categoriaId, $precioMax]);
        } else {
            $stmt = $this->DB->prepare($sql);
            $stmt->execute(['%' . $nombre . '%', $categoriaId]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductoPorCodigo($codigo) {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, 
                Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, 
                Producto.Descripcion AS descripcion, Producto.No_existencias AS no_existencias, 
                Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
                FROM Producto 
                LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
                INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
                WHERE Producto.Codigo = ? AND Producto.No_existencias >= 1";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute([$codigo]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            $producto['precio'] = floatval($producto['precio']);
            $producto['precio_compra'] = floatval($producto['precio_compra']);
            $producto['no_existencias'] = intval($producto['no_existencias']);
        }

        return $producto;
    }
    
    public function generar_folio_venta($id_cliente, $productos) {
        $id_usuario = 3; // ID de usuario predeterminado
        try {
            $this->DB->beginTransaction();
    
            // Insertar la venta
            $stmt = $this->DB->prepare("INSERT INTO Venta (id_usuario, id_cliente, Fecha_hora) VALUES (:id_usuario, :id_cliente, NOW())");
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->execute();
    
            // Obtener el ID de la venta insertada
            $id_venta = $this->DB->lastInsertId();
    
            // Insertar los productos de la venta y actualizar existencias
            foreach ($productos as $producto) {
                // Insertar producto
                $stmt = $this->DB->prepare("INSERT INTO Producto_Venta (id_venta, Codigo_producto, Cantidad, Subtotal) VALUES (:id_venta, :Codigo_producto, :Cantidad, :Subtotal)");
                $stmt->bindParam(':id_venta', $id_venta);
                $stmt->bindParam(':Codigo_producto', $producto['id_producto']);
                $stmt->bindParam(':Cantidad', $producto['cantidad']);
                $stmt->bindParam(':Subtotal', $producto['subtotal']);
                $stmt->execute();
    
                // Actualizar existencias del producto
                $stmt = $this->DB->prepare("UPDATE Producto SET No_existencias = No_existencias - :cantidad WHERE Codigo = :id_producto");
                $stmt->bindParam(':cantidad', $producto['cantidad']);
                $stmt->bindParam(':id_producto', $producto['id_producto']);
                $stmt->execute();
            }
    
            $this->DB->commit();
            return true;
        } catch (Exception $e) {
            $this->DB->rollBack();
            error_log('Error en generar_folio_venta: ' . $e->getMessage());
            return false;
        }
    }
    
}
?>
