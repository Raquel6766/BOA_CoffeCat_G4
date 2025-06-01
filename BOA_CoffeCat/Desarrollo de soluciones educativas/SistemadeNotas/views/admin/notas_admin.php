<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login/login.php");
    exit();
}
require_once '../../models/Nota.php';
$nota = new Nota();
$notas = $nota->obtenerTodasLasNotas();
include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notas del Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_admin.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Listado de Notas</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Estudiante</th>
                        <th>Asignatura</th>
                        <th>Curso</th>
                        <th>Nota</th>
                        <th>Comentario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notas as $n): ?>
                        <tr>
                            <td><?= $n['id_nota'] ?></td>
                            <td><?= $n['estudiante'] ?></td>
                            <td><?= $n['asignatura'] ?></td>
                            <td><?= $n['curso'] ?></td>
                            <td><?= $n['valor_nota'] ?></td>
                            <td><?= $n['comentarios'] ?></td>
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
