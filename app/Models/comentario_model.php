<?php
class comentario_model{
    private $DB;

    public function __construct() {
        $this->DB = Database::connect();
    }

    public function create($data) {
        $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'INSERT INTO comentario (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)';
        $query = $this->DB->prepare($sql);
        $query->execute([$data['nombre'], $data['email'], $data['asunto'], $data['mensaje']]);
        Database::disconnect();
    }
}

?>