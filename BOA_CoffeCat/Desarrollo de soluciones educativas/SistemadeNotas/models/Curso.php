<?php
require_once __DIR__ . "/../config/db.php";

class Curso
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerCursos()
    {
        $stmt = $this->conn->prepare("SELECT c.id_curso, c.grado, c.anio_lectivo FROM curso c");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCursosPorDocente($id_docente)
    {
        $stmt = $this->conn->prepare("
            SELECT DISTINCT c.id_curso, c.grado, c.anio_lectivo
            FROM curso c
            JOIN asignatura a ON c.id_curso = a.id_curso
            JOIN asignatura_docente ad ON a.id_asignatura = ad.id_asignatura
            WHERE ad.id_usuario_docente = ?
            ORDER BY c.anio_lectivo DESC, c.grado
        ");
        $stmt->execute([$id_docente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function agregarCurso($grado, $anio_lectivo)
    {
        $stmt = $this->conn->prepare("INSERT INTO curso(grado, anio_lectivo) VALUES (?, ?)");
        $ok = $stmt->execute([$grado, $anio_lectivo]);
        return $ok;
    }
    public function getLastInsertId()
    {
        return $this->conn->lastInsertId();
    }
    public function getCursoById($id)
    {
        $stmt = $this->conn->prepare("SELECT id_curso, grado, anio_lectivo FROM curso WHERE id_curso = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminarCurso($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM curso WHERE id_curso = ?");
        return $stmt->execute([$id]);
    }
}
