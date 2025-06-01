<?php
require_once '../../models/Curso.php';
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}

$curso = new Curso();
$id_docente = $_SESSION['id'];
$cursos = $curso->obtenerCursosPorDocente($id_docente);
include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mis Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"/>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../templates/sidebar_docente.php'; ?> 
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
                <h3>Mis Cursos</h3>
                <table id="tablaCursos" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Grado</th>
                            <th>Año lectivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cursos as $c): ?>
                        <tr>
                            <td><?= $c['id_curso'] ?></td>
                            <td><?= $c['grado'] ?></td>
                            <td><?= $c['anio_lectivo'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaCursos').DataTable({
                language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" }
            });
        });
    </script>
</body>
</html>