<?php
require_once __DIR__ . '/../../models/Curso.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login/login.php");
    exit();
}

$curso = new Curso();
$cursos = $curso->obtenerCursos();

include __DIR__ . '/../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../templates/sidebar_admin.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Gestión de Cursos</h3>
            <button id="btnAbrirModal" class="btn btn-primary mb-3">Agregar Curso</button>

            <!-- Modal Agregar Curso -->
            <div id="modalFormulario" class="modal" style="display:none; background-color: rgba(0,0,0,0.5); position:fixed; top:0; left:0; width:100%; height:100%; justify-content:center; align-items:center;">
                <div class="modal-dialog">
                    <div class="modal-content p-3">
                        <form id="formAgregarCurso">
                            <div class="mb-3">
                                <label for="grado" class="form-label">Grado</label>
                                <input type="text" id="grado" name="grado" class="form-control" placeholder="Ejemplo: 7º" required>
                            </div>
                            <div class="mb-3">
                                <label for="anio_lectivo" class="form-label">Año lectivo</label>
                                <input type="text" id="anio_lectivo" name="anio_lectivo" class="form-control" placeholder="Ejemplo: 2025" required>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success me-2">Agregar</button>
                                <button type="button" id="btnCerrarModal" class="btn btn-secondary">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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
                        <td><?= htmlspecialchars($c['id_curso']) ?></td>
                        <td><?= htmlspecialchars($c['grado']) ?></td>
                        <td><?= htmlspecialchars($c['anio_lectivo']) ?></td>
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

    // Abrir modal
    $('#btnAbrirModal').on('click', function() {
        $('#modalFormulario').css('display', 'flex');
    });

    // Cerrar modal
    $('#btnCerrarModal').on('click', function() {
        $('#modalFormulario').hide();
        $('#formAgregarCurso')[0].reset();
    });

    // Enviar formulario para agregar curso
    $('#formAgregarCurso').on('submit', function(e) {
        e.preventDefault();
        const data = new FormData(this);
        data.append('action', 'agregar');

        fetch('../../controllers/CursoController.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(res => {
            if(res.success){
                Swal.fire('Éxito', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        });
    });
});
</script>
</body>
</html>
<?php include __DIR__ . '/../templates/footer.php'; ?>


