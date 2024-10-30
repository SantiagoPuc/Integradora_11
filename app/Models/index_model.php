<?php
class index_model {
    private $DB;

    public function __construct() {
        $this->DB = Database::connect();
    }

    public function getProductos() {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, Producto.Descripcion AS descripcion, 
        Producto.No_existencias AS existencias, Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
        FROM Producto 
        LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
        INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
        LIMIT 10;";
        $result = $this->DB->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductosPorCategoria($categoriaId) {
        $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, Producto.Descripcion AS descripcion, 
        Producto.No_existencias AS existencias, Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
        FROM Producto 
        LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
        INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria
        WHERE Producto.id_categoria = :categoriaId";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':categoriaId', $categoriaId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
