<?php
session_start();
require_once '../models/ListaParticipante.php';

header('Content-Type: application/json');

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$lista = new ListaParticipante();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'agregar') {
        $estudiante = $_POST['estudiante'] ?? null;
        $asig_doc = $_POST['asig_doc'] ?? null;

        if (!$estudiante || !$asig_doc) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit();
        }

        // Verificar que no exista la matrícula duplicada antes de agregar (opcional)
        // ...

        $resultado = $lista->agregarMatricula($estudiante, $asig_doc);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Matrícula agregada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar la matrícula']);
        }
        exit();
    }
}

// GET requests and demás manejos...
echo json_encode(['success' => false, 'message' => 'Método no permitido']);
exit;
