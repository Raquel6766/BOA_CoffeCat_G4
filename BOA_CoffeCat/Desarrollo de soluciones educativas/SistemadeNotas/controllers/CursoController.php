<?php
session_start();
require_once "../models/Curso.php";

$curso = new Curso();

// Eliminación AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar') {
    $response = ['success' => false];
    if (isset($_POST['id']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
        $ok = $curso->eliminarCurso($_POST['id']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Curso eliminado correctamente.' : 'No se pudo eliminar el curso.';
    } else {
        $response['message'] = 'Operación no permitida.';
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar') {
    $response = ['success' => false];
    if (
        isset($_POST['grado'], $_POST['anio_lectivo']) &&
        isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin' &&
        !empty($_POST['grado']) && !empty($_POST['anio_lectivo'])
    ) {
        $ok = $curso->agregarCurso(trim($_POST['grado']), intval($_POST['anio_lectivo']));
        if ($ok) {
            $lastId = $curso->getLastInsertId();
            $cursoData = $curso->getCursoById($lastId);
            $response['success'] = true;
            $response['message'] = "Curso agregado correctamente.";
            $response['curso'] = $cursoData;
        } else {
            $response['message'] = "No se pudo agregar el curso.";
        }
    } else {
        $response['message'] = 'Datos incompletos o no autorizado.';
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Si llegas aquí, es petición normal (recarga para seguridad)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'], $_POST['anio_lectivo']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'docente') {
    $curso->agregarCurso($_POST['nombre'], $_POST['anio_lectivo']);
    header("Location: ../views/docente/cursos.php");
    exit;
}
?>