<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../views/login/login.php");
    exit();
}

require_once '../models/Usuario.php';

$usuarioModel = new Usuario();
$usuario = $usuarioModel->obtenerUsuarioPorId($_SESSION['id']);

if (!$usuario) {
    echo "Usuario no encontrado";
    exit();
}

require_once '../views/usuario_perfil.php';
