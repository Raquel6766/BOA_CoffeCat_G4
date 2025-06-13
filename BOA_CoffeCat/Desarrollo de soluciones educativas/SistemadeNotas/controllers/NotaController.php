<?php
session_start();
require_once "../models/Nota.php";

$nota = new Nota();
$id_docente = $_SESSION['id'] ?? null;
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Agregar nota por AJAX
    if (isset($_POST['action']) && $_POST['action'] === 'agregar') {
        $response = ['success' => false];
        if (
            isset($_POST['valor_nota'], $_POST['comentarios'], $_POST['id_lista']) &&
            $id_docente
        ) {
            $nuevaNota = $nota->agregarNota(
                $_POST['valor_nota'],
                $_POST['comentarios'],
                $_POST['id_lista']

            );
            if ($nuevaNota) {
                $response['success'] = true;
                $response['message'] = "Nota agregada correctamente.";
                $response['nota'] = $nuevaNota;
            } else {
                $response['message'] = "No se pudo agregar la nota.";
            }
        } else {
            $response['message'] = 'Datos incompletos.';
        }
        echo json_encode($response);
        exit;
    }

    // Editar nota por AJAX
    if (isset($_POST['action']) && $_POST['action'] === 'editar') {
        $response = ['success' => false];
        if (
            isset($_POST['id'], $_POST['valor_nota'], $_POST['comentarios']) &&
            $id_docente
        ) {
            $ok = $nota->editarNota(
                $_POST['id'],
                $_POST['valor_nota'],
                $_POST['comentarios'],
                $id_docente
            );
            if ($ok) {
                $response['success'] = true;
                $response['message'] = "Nota actualizada correctamente.";
            } else {
                $response['message'] = "No se pudo actualizar la nota.";
            }
        } else {
            $response['message'] = 'Datos incompletos.';
        }
        echo json_encode($response);
        exit;
    }

    // Eliminar nota por AJAX
    if (isset($_POST['action']) && $_POST['action'] === 'eliminar') {
        $response = ['success' => false];
        if (isset($_POST['id']) && $id_docente) {
            $ok = $nota->eliminarNota($_POST['id'], $id_docente);
            if ($ok) {
                $response['success'] = true;
                $response['message'] = "Nota eliminada correctamente.";
            } else {
                $response['message'] = "No se pudo eliminar la nota.";
            }
        } else {
            $response['message'] = 'Datos incompletos.';
        }
        echo json_encode($response);
        exit;
    }
}

// fallback
header("Location: ../views/docente/notas.php");
exit;
?>