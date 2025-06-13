<?php
session_start();

if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] == 'docente') {
        header('Location: ../views/docente/dashboard.php');
    } elseif ($_SESSION['rol'] == 'estudiante') {
        header('Location: ../views/estudiante/dashboard.php');
    } elseif ($_SESSION['rol'] == 'admin') {
        header('Location: ../views/admin/dashboard.php');
    } else {
        session_destroy();
        header('Location: ../views/login/login.php');
    }
} else {
    header('Location: ../views/login/login.php');
}
?>
