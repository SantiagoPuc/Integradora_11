<?php
    class Adm_Productos_model{
        private $DB;

        public function __construct() {
            $this->DB = Database::connect();
        }

        public function get() {
            $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, Producto.Descripcion AS descripcion, 
            Producto.No_existencias AS existencias, Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto FROM Producto 
            LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria;";
            $result = $this->DB->query($sql);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        

        public function create($data){
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'insert into producto(no_serie,nombre,precio,modelo,no_existencias,descripcion,id_marca,id_categoria,precio_compra,foto) values (?,?,?,?,?,?,?,?,?,?)';
            $query = $this->DB->prepare($sql);
            $query->execute([$data['no_serie'], $data['nombre'], $data['precio'], $data['modelo'], $data['no_existencias'] ,$data['descripcion'], $data['id_marca'], $data['id_categoria'],$data['precio_compra'], $data['foto']]);
            Database::disconnect();
        }

        public function get_id($codigo) {
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT * FROM producto WHERE codigo = ?';
            $q = $this->DB->prepare($sql);
            $q->execute(array($codigo));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            return $data;
        }

        public function update($data) {
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'UPDATE producto SET no_serie = ?, nombre = ?, precio = ?, modelo = ?, descripcion = ?, id_marca = ?, id_categoria = ?, precio_compra = ?, foto = ? WHERE codigo = ?';
            $query = $this->DB->prepare($sql);
            $query->execute([$data['no_serie'], $data['nombre'], $data['precio'], $data['modelo'], $data['descripcion'], $data['id_marca'], $data['id_categoria'], $data['precio_compra'],$data['foto'], $data['codigo']]);
            Database::disconnect();
        }
    
        public function delete($codigo) {
            try {
                $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "DELETE FROM producto WHERE codigo = ?";
                $q = $this->DB->prepare($sql);
                return $q->execute([$codigo]);
            } catch (PDOException $e) {
                return false;
            }
        }

        public function get_producto2($codigo) {
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, Producto.Descripcion AS descripcion, 
            Producto.No_existencias AS existencias, Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
            FROM Producto 
            LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
            INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria 
            WHERE Producto.Codigo = ?";
            $q = $this->DB->prepare($sql);
            $q->execute([$codigo]);
            $data = $q->fetch(PDO::FETCH_ASSOC);
            return $data;
        }

        public function search($query) {
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT Producto.Codigo AS codigo, Producto.No_serie AS no_serie, Producto.Nombre AS nombre, Producto.Precio AS precio, Producto.Precio_compra AS precio_compra, Producto.Modelo AS modelo, Producto.Descripcion AS descripcion, 
                    Producto.No_existencias AS existencias, Marca.Nombre_marca AS marca, Categoria.Nombre_categoria AS categoria, Producto.Foto AS foto 
                    FROM Producto 
                    LEFT JOIN Marca ON Producto.id_marca = Marca.ID_marca 
                    INNER JOIN Categoria ON Producto.id_categoria = Categoria.ID_categoria 
                    WHERE Producto.Codigo LIKE ? OR Producto.Nombre LIKE ? OR Producto.No_serie LIKE ? OR Marca.Nombre_marca LIKE ?";
            $query = "%$query%";
            $q = $this->DB->prepare($sql);
            $q->execute([$query, $query, $query, $query]);
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        
        


    }
    //meee
?>