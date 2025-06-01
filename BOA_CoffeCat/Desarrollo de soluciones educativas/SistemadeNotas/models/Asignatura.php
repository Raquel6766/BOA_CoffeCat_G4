<?php
require_once __DIR__ . '/../config/db.php';

class Asignatura {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Mostrar solo asignaturas impartidas por el docente actual
    public function obtenerAsignaturasPorDocente($id_docente)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                ad.id_asig_doc,
                a.nombre_asignatura,
                c.grado,
                c.anio_lectivo
            FROM asignatura_docente ad
            JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
            JOIN curso c ON a.id_curso = c.id_curso
            WHERE ad.id_usuario_docente = ?
        ");
        $stmt->execute([$id_docente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodasConCursoYDocente()
    {
        $stmt = $this->conn->prepare("
            SELECT 
                ad.id_asig_doc,
                a.nombre_asignatura,
                c.grado,
                c.anio_lectivo,
                CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS docente
            FROM asignatura_docente ad
            JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
            JOIN curso c ON a.id_curso = c.id_curso
            JOIN usuario u ON ad.id_usuario_docente = u.id_usuario
            ORDER BY c.anio_lectivo DESC, c.grado
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function agregarAsignatura($nombre, $curso_id, $id_docente) {
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("
                INSERT INTO asignatura(nombre_asignatura, id_curso)
                VALUES (?, ?)
            ");
            $stmt->execute([$nombre, $curso_id]);
            $id_asignatura = $this->conn->lastInsertId();

            // Relacionar asignatura con docente
            $stmt2 = $this->conn->prepare("
                INSERT INTO asignatura_docente(id_usuario_docente, id_asignatura)
                VALUES (?, ?)
            ");
            $stmt2->execute([$id_docente, $id_asignatura]);

            $this->conn->commit();
            return $id_asignatura;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function eliminarAsignatura($id_asignatura, $id_usuario, $rol) {
        if ($rol === 'admin') {
            $stmt = $this->conn->prepare("DELETE FROM asignatura WHERE id_asignatura = ?");
            return $stmt->execute([$id_asignatura]);
        } elseif ($rol === 'docente') {
            $stmt = $this->conn->prepare("
                DELETE a FROM asignatura a
                INNER JOIN asignatura_docente ad ON a.id_asignatura = ad.id_asignatura
                WHERE a.id_asignatura = ? AND ad.id_usuario_docente = ?
            ");
            return $stmt->execute([$id_asignatura, $id_usuario]);
        }
        return false;
    }


    public function getAsignaturaById($id_asignatura) {
        $stmt = $this->conn->prepare("
            SELECT a.id_asignatura, a.nombre_asignatura, c.grado AS nombre_curso
            FROM asignatura a
            JOIN curso c ON a.id_curso = c.id_curso
            WHERE a.id_asignatura = ?
        ");
        $stmt->execute([$id_asignatura]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>