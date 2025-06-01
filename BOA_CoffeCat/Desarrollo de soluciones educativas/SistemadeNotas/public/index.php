<?php
session_start();
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] == 'docente') {
        header('Location: ../views/docente/dashboard.php');
    } else if ($_SESSION['rol'] == 'estudiante') {
        header('Location: ../views/estudiante/dashboard.php');
    }
} else {
    header('Location: ../views/login/login.php');
}
?>
