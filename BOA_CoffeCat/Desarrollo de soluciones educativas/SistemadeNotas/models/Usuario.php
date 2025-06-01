<?php
require_once __DIR__ . '/../config/db.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Obtener todos los docentes
    public function obtenerDocentes()
    {
        $stmt = $this->conn->prepare("
            SELECT id_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo
            FROM usuario
            WHERE id_rol = (SELECT id_rol FROM rol_usuario WHERE nombre_rol = 'docente')
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los estudiantes
    public function obtenerEstudiantes()
    {
        $stmt = $this->conn->prepare("
            SELECT id_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo
            FROM usuario
            WHERE id_rol = (SELECT id_rol FROM rol_usuario WHERE nombre_rol = 'estudiante')
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por su ID
    public function obtenerUsuarioPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar usuarios por nombre o apellido (admin)
    public function buscarUsuarios($texto)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM usuario
            WHERE primer_nombre LIKE ? OR primer_apellido LIKE ?
        ");
        $search = "%$texto%";
        $stmt->execute([$search, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregar nuevo usuario
    public function agregarUsuario($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO usuario (nombre_usuario, contrasena, id_rol, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, telefono)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $datos['nombre_usuario'],
            $datos['contrasena'], // Ya debe venir hasheada
            $datos['id_rol'],
            $datos['primer_nombre'],
            $datos['segundo_nombre'],
            $datos['primer_apellido'],
            $datos['segundo_apellido'],
            $datos['correo'],
            $datos['telefono']
        ]);
    }

    // Eliminar usuario por ID
    public function eliminarUsuario($id_usuario)
    {
        $stmt = $this->conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        return $stmt->execute([$id_usuario]);
    }

    
}
