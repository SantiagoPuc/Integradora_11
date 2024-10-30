<?php
require_once('../bd/conexion.php');

class Adm_Proveedores_model {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function buscar_proveedor($query) {
        $sql = "SELECT p.*, c.Nombre AS Ciudad, e.Nombre AS Estado, pa.Nombre AS Pais
                FROM Proveedor p
                JOIN Ciudad c ON p.id_ciudad = c.ID_ciudad
                JOIN Estado e ON c.id_estado = e.ID_estado
                JOIN Pais pa ON e.id_pais = pa.ID_pais
                WHERE (p.nombre LIKE ? OR p.id_proveedor LIKE ?) AND p.id_EstadoP = 1"; // Filtrar por estado activo
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function get_activos() {
        $sql = "SELECT p.*, c.Nombre AS Ciudad, e.Nombre AS Estado, pa.Nombre AS Pais
                FROM Proveedor p
                JOIN Ciudad c ON p.id_ciudad = c.ID_ciudad
                JOIN Estado e ON c.id_estado = e.ID_estado
                JOIN Pais pa ON e.id_pais = pa.ID_pais
                WHERE p.id_EstadoP = 1"; // 1 for Activo
        $result = $this->db->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    


    public function get_paises() {
        $query = "SELECT ID_pais, Nombre FROM Pais";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadosByPais($idPais) {
        $stmt = $this->db->prepare("SELECT ID_estado, Nombre FROM Estado WHERE ID_pais = ?");
        $stmt->execute([$idPais]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCiudadesByEstado($idEstado) {
        $stmt = $this->db->prepare("SELECT ID_ciudad, Nombre FROM Ciudad WHERE ID_estado = ?");
        $stmt->execute([$idEstado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function get_marcas() {
        $query = "SELECT ID_marca, Nombre_marca FROM Marca";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add_proveedor($data) {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO Proveedor (Nombre, Apellido_1, Apellido_2, Telefono, RFC, Calle, Numero, CP, Cruzamientos, Colonia, id_ciudad, id_EstadoP, foto) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['nombre'],
                $data['primer_apellido'],
                $data['segundo_apellido'],
                $data['telefono'],
                $data['rfc'],
                $data['calle'],
                $data['numero'],
                $data['codigo_postal'],
                $data['cruzamientos'],
                $data['colonia'],
                $data['id_ciudad'],
                $data['foto']
            ]);

            $idProveedor = $this->db->lastInsertId();

            foreach ($data['marcas'] as $marca) {
                $sqlMarca = "INSERT INTO Marca_Proveedor (id_marca, id_proveedor) VALUES (?, ?)";
                $stmtMarca = $this->db->prepare($sqlMarca);
                $stmtMarca->execute([$marca, $idProveedor]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function get_marcas_proveedor($id_proveedor) {
        $sql = "SELECT id_marca FROM Marca_Proveedor WHERE id_proveedor = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_proveedor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_proveedor($data) {
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE Proveedor 
                    SET Nombre = ?, Apellido_1 = ?, Apellido_2 = ?, Telefono = ?, RFC = ?, Calle = ?, Numero = ?, CP = ?, Cruzamientos = ?, Colonia = ?, id_ciudad = ?, foto = ? 
                    WHERE ID_proveedor = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['nombre'],
                $data['primer_apellido'],
                $data['segundo_apellido'],
                $data['telefono'],
                $data['rfc'],
                $data['calle'],
                $data['numero'],
                $data['codigo_postal'],
                $data['cruzamientos'],
                $data['colonia'],
                $data['id_ciudad'],
                $data['foto'],
                $data['id']
            ]);

            // Borrar marcas anteriores
            $sqlDeleteMarcas = "DELETE FROM Marca_Proveedor WHERE id_proveedor = ?";
            $stmtDelete = $this->db->prepare($sqlDeleteMarcas);
            $stmtDelete->execute([$data['id']]);

            // Insertar nuevas marcas
            foreach ($data['marcas'] as $marca) {
                $sqlMarca = "INSERT INTO Marca_Proveedor (id_marca, id_proveedor) VALUES (?, ?)";
                $stmtMarca = $this->db->prepare($sqlMarca);
                $stmtMarca->execute([$marca, $data['id']]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function toggle_estado($id, $estado) {
        $sql = "UPDATE Proveedor SET id_EstadoP = ? WHERE ID_proveedor = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }
    

    public function get_proveedor($id) {
        $sql = "SELECT p.*, c.id_estado, e.id_pais 
                FROM Proveedor p
                JOIN Ciudad c ON p.id_ciudad = c.ID_ciudad
                JOIN Estado e ON c.id_estado = e.ID_estado
                WHERE p.ID_proveedor = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Obtén las marcas asociadas
        $sqlMarcas = "SELECT id_marca FROM Marca_Proveedor WHERE id_proveedor = ?";
        $stmtMarcas = $this->db->prepare($sqlMarcas);
        $stmtMarcas->execute([$id]);
        $result['marcas'] = $stmtMarcas->fetchAll(PDO::FETCH_COLUMN);
    
        return $result;
    }
    

    public function get_proveedor_full($id) {
        $sql = "SELECT p.*, c.Nombre AS Ciudad, e.Nombre AS Estado, pa.Nombre AS Pais
                FROM Proveedor p
                JOIN Ciudad c ON p.id_ciudad = c.ID_ciudad
                JOIN Estado e ON c.id_estado = e.ID_estado
                JOIN Pais pa ON e.id_pais = pa.ID_pais
                WHERE p.ID_proveedor = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_proveedor_detalles($id) {
        $sql = "SELECT p.*, c.Nombre AS Ciudad, e.Nombre AS Estado, pa.Nombre AS Pais
                FROM Proveedor p
                JOIN Ciudad c ON p.id_ciudad = c.ID_ciudad
                JOIN Estado e ON c.id_estado = e.ID_estado
                JOIN Pais pa ON e.id_pais = pa.ID_pais
                WHERE p.ID_proveedor = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($proveedor) {
            $sqlMarcas = "SELECT m.Nombre_marca 
                          FROM Marca_Proveedor mp
                          JOIN Marca m ON mp.id_marca = m.ID_marca
                          WHERE mp.id_proveedor = ?";
            $stmtMarcas = $this->db->prepare($sqlMarcas);
            $stmtMarcas->execute([$id]);
            $proveedor['marcas'] = $stmtMarcas->fetchAll(PDO::FETCH_COLUMN);
        }
    
        return $proveedor;
    }
    
    
    
    public function rfc_exists($rfc, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM Proveedor WHERE RFC = ?";
        $params = [$rfc];
        
        if ($exclude_id) {
            $sql .= " AND ID_proveedor != ?";
            $params[] = $exclude_id;
        }
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    

    public function get_inactivos() {
        $sql = "SELECT id_proveedor, nombre, apellido_1, apellido_2, telefono FROM Proveedor WHERE id_EstadoP = 2"; // 2 for Inactivo
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function habilitar_proveedor($id) {
        $sql = "UPDATE Proveedor SET id_EstadoP = 1 WHERE ID_proveedor = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
}
?>
