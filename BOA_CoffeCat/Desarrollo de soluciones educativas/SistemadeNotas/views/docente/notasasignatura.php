<?php
require_once __DIR__ . '/../../models/Nota.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}

if (!isset($_GET['id_asig_doc'])) {
    echo "ID de asignatura no proporcionado.";
    exit();
}

$id_asig_doc = $_GET['id_asig_doc'];
$notaModel = new Nota();
$notas = $notaModel->obtenerNotasPorAsignaturaDocente($id_asig_doc);

include __DIR__ . '/../templates/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notas por Asignatura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../templates/sidebar_docente.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Notas de la Asignatura</h3>
                <a href="asignaturas.php" class="btn btn-secondary">
                    ← Volver a Mis Asignaturas
                </a>
            </div>
            <table id="tablaNotas" class="table table-striped">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Nota</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notas as $n): ?>
                    <tr>
                        <td><?= htmlspecialchars($n['estudiante']) ?></td>
                        <td><?= htmlspecialchars($n['curso']) ?></td>
                        <td>
                            <input type="number" class="form-control form-control-sm input-nota" 
                                   data-id-lista="<?= $n['id_lista'] ?>" 
                                   value="<?= htmlspecialchars($n['valor_nota']) ?>">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm guardar-nota" data-id-lista="<?= $n['id_lista'] ?>">
                                <i class="bi bi-eye"></i> Guardar
                            </button>
                        </td>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#tablaNotas').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" }
    });

    $('.guardar-nota').on('click', function() {
        const id_lista = $(this).data('id-lista');
        const nota = $(`input[data-id-lista="${id_lista}"]`).val();

        $.ajax({
            url: '../../controllers/NotaController.php',
            method: 'POST',
            data: {
                action: 'agregar',
                id_lista: id_lista,
                valor_nota: nota,
                comentarios: '' // puedes agregar un campo si lo necesitas
            },
            success: function(response) {
                if (response.success) {
                    alert('Nota guardada correctamente');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error de conexión al guardar la nota');
            }
        });
    });
});
</script>
</body>
</html>
<?php include __DIR__ . '/../templates/footer.php'; ?>
