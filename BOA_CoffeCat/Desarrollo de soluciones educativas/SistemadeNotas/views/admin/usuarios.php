<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login/login.php");
    exit();
}
require_once '../../models/Usuario.php';
$usuario = new Usuario();
$usuarios = $usuario->buscarUsuarios('');
include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Usuarios del Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_admin.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Listado de Usuarios</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Rol (ID)</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['id_usuario'] ?></td>
                            <td><?= $u['nombre_usuario'] ?></td>
                            <td><?= $u['primer_nombre'] . ' ' . $u['primer_apellido'] ?></td>
                            <td><?= $u['id_rol'] ?></td>
                            <td><?= $u['correo'] ?></td>
                            <td><?= $u['telefono'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>
</body>
</html>
<?php include '../templates/footer.php'; ?>
