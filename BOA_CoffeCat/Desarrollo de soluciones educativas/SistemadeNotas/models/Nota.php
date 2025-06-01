<?php
require_once __DIR__ . '/Asignatura.php';
require_once __DIR__ . '/Curso.php';
require_once __DIR__ . '/../config/db.php';

class Nota
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerNotasPorDocente($id_docente)
    {
        // Solo las notas de asignaturas del docente
        $stmt = $this->conn->prepare("
            SELECT n.id_nota, n.valor_nota, n.comentarios, 
            u.nombre_usuario AS estudiante, 
            a.nombre_asignatura,
            c.grado AS nombre_curso
                FROM nota n
                JOIN lista_participante lp ON n.id_lista = lp.id_lista
                JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
                JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
                JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
                JOIN curso c ON a.id_curso = c.id_curso
                WHERE ad.id_usuario_docente = ?
        ");
        $stmt->execute([$id_docente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarNota($valor_nota, $comentarios, $id_lista) {
        // Primero verificamos si ya existe una nota para ese id_lista
        $sqlCheck = "SELECT id_nota FROM nota WHERE id_lista = ?";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([$id_lista]);
        $notaExistente = $stmtCheck->fetch();

        if ($notaExistente) {
            // Si existe, actualizamos
            $sqlUpdate = "UPDATE nota SET valor_nota = ?, comentarios = ? WHERE id_lista = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->execute([$valor_nota, $comentarios, $id_lista]);

            return [
                'id_nota' => $notaExistente['id_nota'],
                'valor_nota' => $valor_nota,
                'comentarios' => $comentarios,
                'id_lista' => $id_lista,
                'accion' => 'actualizado'
            ];
        } else {
            // Si no existe, insertamos
            $sqlInsert = "INSERT INTO nota (valor_nota, comentarios, id_lista) VALUES (?, ?, ?)";
            $stmtInsert = $this->conn->prepare($sqlInsert);
            $stmtInsert->execute([$valor_nota, $comentarios, $id_lista]);

            $id_nueva_nota = $this->conn->lastInsertId();

            return [
                'id_nota' => $id_nueva_nota,
                'valor_nota' => $valor_nota,
                'comentarios' => $comentarios,
                'id_lista' => $id_lista,
                'accion' => 'insertado'
            ];
        }
    }

    public function getNotaById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT n.ID_nota, n.valor_nota, n.comentarios, 
                u.nombre_usuario AS estudiante, 
                a.nombre_asignatura,
                c.grado AS nombre_curso
            FROM nota n
            JOIN lista_participante lp ON n.lista_participante_ID_lista = lp.ID_lista
            JOIN usuario u ON lp.usuario_ID_usuario = u.ID_usuario
            JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
            JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
            JOIN curso c ON n.curso_ID_curso = c.id_curso
            WHERE n.ID_nota = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminarNota($id, $id_docente)
    {
        // Solo deja eliminar si la nota pertenece a una asignatura del docente
        $stmt = $this->conn->prepare("
            DELETE n FROM nota n
            JOIN lista_participante lp ON n.lista_participante_ID_lista = lp.ID_lista
            JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
            WHERE n.ID_nota = ? AND ad.id_usuario_docente = ?
        ");
        return $stmt->execute([$id, $id_docente]);
    }

    public function editarNota($id, $valor, $comentarios, $id_docente)
    {
        // Solo deja editar si la nota pertenece a una asignatura del docente
        $stmt = $this->conn->prepare("
            UPDATE nota n
            JOIN lista_participante lp ON n.lista_participante_ID_lista = lp.ID_lista
            JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
            SET n.valor_nota = ?, n.comentarios = ?
            WHERE n.ID_nota = ? AND ad.id_usuario_docente = ?
        ");
        return $stmt->execute([$valor, $comentarios, $id, $id_docente]);
    }

    public function obtenerTodasLasNotas()
    {
        $stmt = $this->conn->prepare("
            SELECT 
                n.id_nota,
                n.valor_nota,
                n.comentarios,
                CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS estudiante,
                a.nombre_asignatura AS asignatura,
                c.grado AS curso
            FROM nota n
            JOIN lista_participante lp ON n.id_lista = lp.id_lista
            JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
            JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
            JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
            JOIN curso c ON a.id_curso = c.id_curso
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
