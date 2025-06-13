<?php
require_once '../config/db.php';
session_start();

// Mostrar errores para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT u.*, r.nombre_rol FROM usuario u
                            JOIN rol_usuario r ON u.id_rol = r.id_rol
                            WHERE u.nombre_usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $esCorrecta = password_verify($contrasena, $user['contrasena']) || $contrasena === $user['contrasena'];

        if ($esCorrecta) {
            $_SESSION['id'] = $user['id_usuario'];
            $_SESSION['rol'] = strtolower($user['nombre_rol']);

            switch ($_SESSION['rol']) {
                case 'docente':
                    header("Location: ../views/docente/dashboard.php");
                    exit();
                case 'estudiante':
                    header("Location: ../views/estudiante/dashboard.php");
                    exit();
                case 'admin':
                    header("Location: ../views/admin/dashboard.php");
                    exit();
                default:
                    session_destroy();
                    header("Location: ../views/login/login.php?error=rol_desconocido&intento=1");
                    exit();
            }
        } else {
            header("Location: ../views/login/login.php?error=credenciales&intento=1");
            exit();
        }
    } else {
        header("Location: ../views/login/login.php?error=credenciales&intento=1");
        exit();
    }
}

?>
