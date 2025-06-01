<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $db = new Database();
    $conn = $db->connect();

    // Busca solo por el nombre de usuario
    $stmt = $conn->prepare("SELECT u.*, r.nombre_rol FROM usuario u
                            JOIN rol_usuario r ON u.id_rol = r.id_rol
                            WHERE u.nombre_usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['contrasena']) {
        $_SESSION['id'] = $user['id_usuario'];
        $_SESSION['rol'] = strtolower($user['nombre_rol']);

        if ($_SESSION['rol'] == 'docente') {
            header("Location: ../views/docente/dashboard.php");
            exit();
        } elseif ($_SESSION['rol'] == 'estudiante') {
            header("Location: ../views/estudiante/dashboard.php");
            exit();
        } elseif ($_SESSION['rol'] == 'admin') {
            header("Location: ../views/admin/dashboard.php");
            exit();
        } else {
            session_destroy();
            echo "<script>alert('Rol de usuario desconocido'); window.location.href = '../views/login/login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Credenciales incorrectas'); window.location.href = '../views/login/login.php';</script>";
        exit();
    }
}
?>