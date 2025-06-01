<?php
require_once __DIR__ . '/../../models/Asignatura.php';
require_once __DIR__ . '/../../models/Curso.php';
require_once __DIR__ . '/../../models/Usuario.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login/login.php");
    exit();
}

$asignatura = new Asignatura();
$cursos = (new Curso())->obtenerCursos();
$docentes = (new Usuario())->obtenerDocentes();
$asignaturas = $asignatura->obtenerTodasConCursoYDocente();

include __DIR__ . '/../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestión de Asignaturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../templates/sidebar_admin.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Gestión de Asignaturas</h3>
            <button id="btnAbrirModal" class="btn btn-primary mb-3">Agregar Asignatura</button>
            
            <div id="modalFormulario" class="modal" style="display:none; background-color: rgba(0,0,0,0.5); position:fixed; top:0; left:0; width:100%; height:100%; justify-content:center; align-items:center;">
                <div class="modal-dialog">
                    <div class="modal-content p-3">
                        <form id="formAgregarAsignatura">
                            <div class="mb-2">
                                <input type="text" name="nombre" class="form-control" placeholder="Nombre de asignatura" required>
                            </div>
                            <div class="mb-2">
                                <select name="curso" class="form-select" required>
                                    <option value="">Seleccionar curso</option>
                                    <?php foreach($cursos as $c): ?>
                                        <option value="<?= $c['id_curso'] ?>"><?= $c['grado'] ?> - <?= $c['anio_lectivo'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <select name="docente" class="form-select" required>
                                    <option value="">Asignar docente</option>
                                    <?php foreach($docentes as $d): ?>
                                        <option value="<?= $d['id_usuario'] ?>"><?= $d['primer_nombre'] . ' ' . $d['primer_apellido'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">Agregar</button>
                                <button type="button" id="btnCerrarModal" class="btn btn-secondary">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <table id="tablaAsignaturas" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asignatura</th>
                        <th>Curso</th>
                        <th>Año</th>
                        <th>Docente</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($asignaturas as $a): ?>
                    <tr>
                        <td><?= $a['id_asig_doc'] ?></td>
                        <td><?= $a['nombre_asignatura'] ?></td>
                        <td><?= $a['grado'] ?></td>
                        <td><?= $a['anio_lectivo'] ?></td>
                        <td><?= $a['docente'] ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="eliminarAsignatura(<?= $a['id_asig_doc'] ?>, this)">Eliminar</button>
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

    $('#btnAbrirModal').on('click', () => $('#modalFormulario').css('display', 'flex'));
    $('#btnCerrarModal').on('click', () => $('#modalFormulario').hide());

    $('#formAgregarAsignatura').on('submit', function(e) {
        e.preventDefault();
        const data = new FormData(this);
        data.append('action', 'agregar');

        fetch('../../controllers/AsignaturaController.php', {
            method: 'POST',
            body: data
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Éxito', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
});

function eliminarAsignatura(id, btn) {
    Swal.fire({
        title: '¿Eliminar asignatura?',
        text: 'Esto no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('../../controllers/AsignaturaController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'eliminar', id_asignatura: id })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Eliminado', res.message, 'success');
                    $(btn).closest('tr').remove();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}
</script>
</body>
</html>
<?php include __DIR__ . '/../templates/footer.php'; ?>
