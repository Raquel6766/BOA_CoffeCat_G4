<?php
session_start();
require_once __DIR__ . '/../models/Asignatura.php';

$asignatura = new Asignatura();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // AJAX agregar
    if (isset($_POST['action']) && $_POST['action'] === 'agregar') {
        $response = ['success' => false];
        if (
            isset($_POST['nombre'], $_POST['curso']) &&
            isset($_SESSION['id']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'
        ) {
            $id_docente = intval($_POST['docente']);
            $id_asignatura = $asignatura->agregarAsignatura(
                
                trim($_POST['nombre']),
                intval($_POST['curso']),
                $id_docente
            );
            if ($id_asignatura) {
                $asigData = $asignatura->getAsignaturaById($id_asignatura);
                $response['success'] = true;
                $response['message'] = "Asignatura agregada correctamente.";
                $response['asignatura'] = $asigData;
            } else {
                $response['message'] = "No se pudo agregar la asignatura.";
            }
        } else {
            $response['message'] = 'Datos incompletos o no autorizado.';
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // AJAX eliminar
    if (isset($_POST['action']) && $_POST['action'] === 'eliminar') {
        $response = ['success' => false];
        if (
            isset($_POST['id_asignatura']) &&
            isset($_SESSION['id']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'
        ) {
            $ok = $asignatura->eliminarAsignatura($_POST['id_asignatura'], $_SESSION['id'], $_SESSION['rol']);
            if ($ok) {
                $response['success'] = true;
                $response['message'] = "Asignatura eliminada correctamente.";
            } else {
                $response['message'] = "No se pudo eliminar la asignatura o no tiene permisos.";
            }
        } else {
            $response['message'] = 'Datos incompletos o no autorizado.';
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Si llega por método normal, redirige
header("Location: ../views/docente/asignaturas.php");
exit;
?>