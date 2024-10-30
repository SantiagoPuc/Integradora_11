<?php
require_once('../bd/conexion.php');

class Adm_Clientes_model {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function get() {
        $sql = "SELECT c.*, ci.Nombre AS Ciudad, e.Nombre AS Estado, p.Nombre AS Pais 
                FROM Cliente c
                JOIN Ciudad ci ON c.id_ciudad = ci.id_ciudad
                JOIN Estado e ON ci.id_estado = e.ID_estado
                JOIN Pais p ON e.id_pais = p.ID_pais";
        $result = $this->db->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar_cliente($query) {
        $sql = "SELECT c.*, ci.Nombre AS Ciudad, e.Nombre AS Estado, p.Nombre AS Pais 
                FROM Cliente c
                JOIN Ciudad ci ON c.id_ciudad = ci.id_ciudad
                JOIN Estado e ON ci.id_estado = e.ID_estado
                JOIN Pais p ON e.id_pais = p.ID_pais
                WHERE c.nombre LIKE ? OR c.id_cliente LIKE ?";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function get_paises2() {
        $query = "SELECT ID_pais, Nombre FROM Pais";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function get_cliente_full($id) {
        $sql = "SELECT c.*, ci.ID_pais, ci.ID_estado
                FROM Cliente c
                JOIN Ciudad ci ON c.id_ciudad = ci.ID_ciudad
                WHERE c.ID_cliente = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    
    
    public function get_cliente($id) {
        $sql = "SELECT c.*, p.ID_pais, e.ID_estado, ci.ID_ciudad 
                FROM Cliente c
                JOIN Ciudad ci ON c.id_ciudad = ci.ID_ciudad
                JOIN Estado e ON ci.ID_estado = e.ID_estado
                JOIN Pais p ON e.ID_pais = p.ID_pais
                WHERE c.ID_cliente = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function get_cliente2($id) {
        $sql = "SELECT c.*, ci.Nombre AS ciudad_nombre, e.Nombre AS estado_nombre, p.Nombre AS pais_nombre 
                FROM Cliente c
                JOIN Ciudad ci ON c.id_ciudad = ci.ID_ciudad
                JOIN Estado e ON ci.ID_estado = e.ID_estado
                JOIN Pais p ON e.ID_pais = p.ID_pais
                WHERE c.ID_cliente = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add_cliente($data) {
        $sql = "INSERT INTO Cliente (ID_cliente, Nombre, Apellido_1, Apellido_2, Telefono, Correo, Calle, Numero, CP, Cruzamientos, Colonia, id_ciudad, foto) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['id'],
            $data['nombre'],
            $data['primer_apellido'],
            $data['segundo_apellido'],
            $data['telefono'],
            $data['correo'],
            $data['calle'],
            $data['numero'],
            $data['codigo_postal'],
            $data['cruzamientos'],
            $data['colonia'],
            $data['id_ciudad'],
            $data['foto']
        ]);
    }

    public function add_cliente_login($data) {
        $sql = "INSERT INTO Cliente (ID_cliente, Nombre, Apellido_1, Apellido_2, Telefono, Correo, Calle, Numero, CP, Cruzamientos, Colonia, id_ciudad, foto, passwordd_C) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['id'],
            $data['nombre'],
            $data['primer_apellido'],
            $data['segundo_apellido'],
            $data['telefono'],
            $data['correo'],
            $data['calle'],
            $data['numero'],
            $data['codigo_postal'],
            $data['cruzamientos'],
            $data['colonia'],
            $data['id_ciudad'],
            $data['foto'],
            $data['passwordd_C']
        ]);
    }
    
    public function update_cliente($data) {
        $sql = "UPDATE Cliente 
                SET Nombre = ?, Apellido_1 = ?, Apellido_2 = ?, Telefono = ?, Correo = ?, Calle = ?, Numero = ?, CP = ?, Cruzamientos = ?, Colonia = ?, id_ciudad = ?, foto = ?
                WHERE ID_cliente = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['primer_apellido'],
            $data['segundo_apellido'],
            $data['telefono'],
            $data['correo'],
            $data['calle'],
            $data['numero'],
            $data['codigo_postal'],
            $data['cruzamientos'],
            $data['colonia'],
            $data['id_ciudad'],
            $data['foto'],
            $data['id']
        ]);
    }
    
    

    

    public function delete_cliente($id) {
        try {
            // Obtener la información del cliente antes de eliminarlo
            $cliente = $this->get_cliente($id);
            if (!$cliente) {
                throw new Exception('Cliente no encontrado.');
            }
    
            // Eliminar el registro de la base de datos
            $sql = "DELETE FROM Cliente WHERE ID_cliente = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$id]);
    
            if ($result) {
                // Eliminar la foto si existe
                if ($cliente['foto']) {
                    $fotoPath = __DIR__ . "/../uploads/" . $cliente['foto'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }
                return true;
            } else {
                throw new PDOException('Error al ejecutar la consulta.');
            }
        } catch (PDOException $e) {
            error_log('Error al eliminar el cliente: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function findByUsername($username) {
        $sql = "SELECT * FROM cliente WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function authenticate($username, $password) {
        $sql = "SELECT id_cliente, nombre, apellido_1, foto FROM cliente WHERE correo = ? AND passwordd_C = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>
