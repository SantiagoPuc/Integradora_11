<?php
    class comentario_controller{
        private $model;

        public function __construct() {
            $this->model = new comentario_model();
        }

        public function create() {
            try {
        
                $data = [
                    'nombre' => $_POST['nombre'],
                    'email' => $_POST['email'],
                    'asunto' => $_POST['asunto'],
                    'mensaje' => $_POST['mensaje'],
                    
                ];
                
                $this->model->create($data);
        
            } catch (Exception $e) {
                error_log('Create method failed: ' . $e->getMessage());
                die('Create method failed: ' . $e->getMessage());
            }
        }

    }


?>