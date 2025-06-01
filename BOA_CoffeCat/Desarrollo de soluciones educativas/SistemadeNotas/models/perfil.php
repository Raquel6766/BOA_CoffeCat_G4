<?php
    class perfil{
       private $conn;
       
        public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        }

        public function ObtenerPerfil(){
            $stmt = $this->conn->prepare("
                SELECT p.ID_perfil, p.Nombre, p.Apellido, 
                    u.nombre_usuario AS estudiante, 
                    a.nombre_asignatura 
                FROM Nota n
                JOIN Usuario u ON n.ID_usuario = u.ID_usuario
                JOIN Asignatura a ON n.ID_asignatura = a.ID_asignatura
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>