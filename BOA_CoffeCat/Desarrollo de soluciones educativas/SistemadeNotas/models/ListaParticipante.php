<?php
require_once __DIR__ . '/../config/db.php';

class ListaParticipante
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Obtener todas las asignaturas donde está inscrito un estudiante
    public function obtenerAsignaturasPorEstudiante($id_estudiante)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                lp.id_lista,
                a.nombre_asignatura,
                c.grado,
                c.anio_lectivo,
                u_docente.primer_nombre AS docente_nombre,
                u_docente.primer_apellido AS docente_apellido
            FROM lista_participante lp
            JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
            JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
            JOIN curso c ON a.id_curso = c.id_curso
            JOIN usuario u_docente ON ad.id_usuario_docente = u_docente.id_usuario
            WHERE lp.id_usuario_estudiante = ?
        ");
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los estudiantes inscritos en una asignatura_docente específica
    public function obtenerEstudiantesPorAsignaturaDocente($id_asig_doc)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                u.id_usuario,
                u.primer_nombre,
                u.primer_apellido,
                lp.id_lista
            FROM lista_participante lp
            JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
            WHERE lp.id_asig_doc = ?
        ");
        $stmt->execute([$id_asig_doc]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodas()
    {
        $stmt = $this->conn->prepare("
            SELECT 
                lp.id_lista,
                u_estudiante.primer_nombre AS estudiante_nombre,
                u_estudiante.primer_apellido AS estudiante_apellido,
                a.nombre_asignatura,
                u_docente.primer_nombre AS docente_nombre,
                u_docente.primer_apellido AS docente_apellido
            FROM lista_participante lp
            JOIN usuario u_estudiante ON lp.id_usuario_estudiante = u_estudiante.id_usuario
            JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
            JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
            JOIN usuario u_docente ON ad.id_usuario_docente = u_docente.id_usuario
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarMatricula($id_estudiante, $id_asig_doc)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO lista_participante (id_usuario_estudiante, id_asig_doc)
            VALUES (?, ?)
        ");
        return $stmt->execute([$id_estudiante, $id_asig_doc]);
    }
}
