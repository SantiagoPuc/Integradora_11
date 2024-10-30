<?php
require_once('../bd/conexion.php');

class Adm_reporte_model {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getReporte1() {
        $sql = "SELECT M.Nombre_marca AS Marca, SUM(PV.subtotal) AS Total_de_Venta_del_ultimo_mes
                FROM Producto_Venta PV
                JOIN Producto P ON PV.codigo_producto = P.Codigo
                JOIN Marca M ON P.id_marca = M.ID_marca
                WHERE PV.id_venta IN (SELECT id_venta FROM Venta WHERE Fecha_hora BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW())
                GROUP BY M.Nombre_marca";
        $result = $this->db->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReporte2() {
        $sql = "SELECT Nombre AS Producto_mas_barato, Precio FROM Producto WHERE Precio = (SELECT MIN(Precio) FROM Producto)";
        $result = $this->db->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarProducto($query) {
        $sql = "SELECT P.Codigo, P.Nombre, P.Precio, P.Precio_compra, P.No_existencias, 
                       (SELECT AVG(Cantidad) FROM Producto_Venta WHERE codigo_producto = P.Codigo) AS Demanda_Mensual 
                FROM Producto P 
                WHERE P.Codigo LIKE :query OR P.Nombre LIKE :query";
        $stmt = $this->db->prepare($sql);
        $query = "%$query%";
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReporte3() {
        $sql = "SELECT P.Nombre AS Producto, SUM(PV.Cantidad) AS Total_de_Unidades_Vendidas
                FROM Producto_Venta PV
                JOIN Producto P ON PV.codigo_producto = P.Codigo
                GROUP BY P.Nombre
                ORDER BY SUM(PV.Cantidad) DESC
                LIMIT 1";
        $result = $this->db->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConsumoPromedio($codigoProducto) {
        $sql = "SELECT AVG(Cantidad) as consumo_promedio 
                FROM Producto_Venta 
                WHERE codigo_producto = :codigo_producto";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo_producto', $codigoProducto);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['consumo_promedio'];
    }

    public function getprecio($codigoProducto){
        $sql = "SELECT Precio FROM Producto WHERE Codigo = :codigo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigoProducto);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['Precio'];
    }

    public function getpreciocompra($codigoProducto){
        $sql = "SELECT Precio_compra FROM Producto WHERE Codigo = :codigo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigoProducto);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['Precio_compra'];
    }
}
?>
