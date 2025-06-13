<?php
require_once __DIR__ . '/../../models/Asignatura.php';
require_once __DIR__ . '/../../models/Curso.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}

$asignatura = new Asignatura();
$id_docente = $_SESSION['id'];
$asignaturas = $asignatura->obtenerAsignaturasPorDocente($id_docente);

$curso = new Curso();
$cursos = $curso->obtenerCursos();

include __DIR__ . '/../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mis Asignaturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../templates/sidebar_docente.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Mis Asignaturas</h3>
            <table id="tablaAsignaturas" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asignatura</th>
                        <th>Curso</th>
                        <th>Año Lectivo</th>
                        <th>Acciones</th> <!-- Nueva columna -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($asignaturas as $a): ?>
                    <tr>
                        <td><?= $a['id_asig_doc'] ?></td>
                        <td><?= $a['nombre_asignatura'] ?></td>
                        <td><?= $a['grado'] ?></td>
                        <td><?= $a['anio_lectivo'] ?></td>
                        <td>
                            <a href="../docente/notasasignatura.php?id_asig_doc=<?= $a['id_asig_doc'] ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
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
    $('#tablaAsignaturas').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" }
    });
});
</script>
</body>
</html>
<?php include __DIR__ . '/../templates/footer.php'; ?>