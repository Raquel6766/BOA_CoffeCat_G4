<?php
session_start();
require_once '../models/Usuario.php';

$usuario = new Usuario();
header('Content-Type: application/json');

// Verifica si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Agregar usuario
    if (isset($_POST['action']) && $_POST['action'] === 'agregar') {
        $response = ['success' => false];

        // Validar campos
        $campos = ['nombre_usuario', 'contrasena', 'id_rol', 'primer_nombre', 'primer_apellido'];
        $faltantes = array_filter($campos, fn($c) => empty($_POST[$c]));

        if (empty($faltantes)) {
            // Preparar datos y encriptar contraseña
            $datos = [
                'nombre_usuario' => $_POST['nombre_usuario'],
                'contrasena' => password_hash($_POST['contrasena'], PASSWORD_BCRYPT),
                'id_rol' => $_POST['id_rol'],
                'primer_nombre' => $_POST['primer_nombre'],
                'segundo_nombre' => $_POST['segundo_nombre'] ?? null,
                'primer_apellido' => $_POST['primer_apellido'],
                'segundo_apellido' => $_POST['segundo_apellido'] ?? null,
                'correo' => $_POST['correo'] ?? null,
                'telefono' => $_POST['telefono'] ?? null
            ];

            $ok = $usuario->agregarUsuario($datos);
            if ($ok) {
                $response['success'] = true;
                $response['message'] = 'Usuario agregado correctamente.';
            } else {
                $response['message'] = 'Error al agregar usuario.';
            }
        } else {
            $response['message'] = 'Faltan campos obligatorios: ' . implode(', ', $faltantes);
        }

        echo json_encode($response);
        exit;
    }

    // Eliminar usuario
    if (isset($_POST['action']) && $_POST['action'] === 'eliminar') {
        $response = ['success' => false];
        if (!empty($_POST['id_usuario'])) {
            $ok = $usuario->eliminarUsuario($_POST['id_usuario']);
            if ($ok) {
                $response['success'] = true;
                $response['message'] = 'Usuario eliminado correctamente.';
            } else {
                $response['message'] = 'No se pudo eliminar el usuario.';
            }
        } else {
            $response['message'] = 'ID de usuario requerido.';
        }
        echo json_encode($response);
        exit;
    }

    // Buscar usuario
    if (isset($_POST['action']) && $_POST['action'] === 'buscar') {
        $texto = $_POST['query'] ?? '';
        $usuarios = $usuario->buscarUsuarios($texto);
        echo json_encode([
            'success' => true,
            'resultados' => $usuarios
        ]);
        exit;
    }
}

// Si no coincide ninguna acción
echo json_encode(['success' => false, 'message' => 'Petición no válida.']);
exit;
