<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login/login.php");
    exit();
}
require_once '../../models/ListaParticipante.php';
require_once '../../models/Usuario.php';
require_once '../../models/Asignatura.php';

$lista = new ListaParticipante();
$usuarios = (new Usuario())->obtenerEstudiantes();
$asignaturas = (new Asignatura())->obtenerTodasConCursoYDocente();
$matriculas = $lista->obtenerTodas();

include '../templates/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Matrículas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_admin.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Listado de Matrículas</h3>

            <button id="btnAbrirModal" class="btn btn-primary mb-3">Agregar Matrícula</button>

            <!-- Modal para agregar matrícula -->
            <div id="modalFormulario" class="modal" style="display:none; background-color: rgba(0,0,0,0.5); position:fixed; top:0; left:0; width:100%; height:100%; justify-content:center; align-items:center;">
                <div class="modal-dialog">
                    <div class="modal-content p-3">
                        <form id="formAgregarMatricula">
                            <div class="mb-3">
                                <label class="form-label">Estudiante:</label>
                                <select name="estudiante" class="form-select" required>
                                    <option value="">Seleccionar estudiante</option>
                                    <?php foreach ($usuarios as $u): ?>
                                        <option value="<?= $u['id_usuario'] ?>">
                                            <?= htmlspecialchars($u['primer_nombre'] . ' ' . $u['primer_apellido']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Asignatura:</label>
                                <select name="asig_doc" class="form-select" required>
                                    <option value="">Seleccionar asignatura</option>
                                    <?php foreach ($asignaturas as $a): ?>
                                        <option value="<?= $a['id_asig_doc'] ?>">
                                            <?= htmlspecialchars($a['nombre_asignatura'] . ' - ' . $a['grado'] . ' (' . $a['docente'] . ')') ?>
                                        </option>
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

            <!-- Tabla con DataTables -->
            <table id="tablaMatriculas" class="table table-striped table-bordered" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Estudiante</th>
                        <th>Asignatura</th>
                        <th>Docente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriculas as $m): ?>
                        <tr>
                            <td><?= $m['id_lista'] ?></td>
                            <td><?= htmlspecialchars($m['estudiante_nombre'] . ' ' . $m['estudiante_apellido']) ?></td>
                            <td><?= htmlspecialchars($m['nombre_asignatura']) ?></td>
                            <td><?= htmlspecialchars($m['docente_nombre'] . ' ' . $m['docente_apellido']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaMatriculas').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    $('#btnAbrirModal').on('click', () => $('#modalFormulario').css('display', 'flex'));
    $('#btnCerrarModal').on('click', () => $('#modalFormulario').hide());

    $('#formAgregarMatricula').on('submit', function(e) {
        e.preventDefault();
        const data = new FormData(this);
        data.append('action', 'agregar');

        fetch('../../controllers/ListaParticipanteController.php', {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                Swal.fire('¡Éxito!', resp.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', resp.message, 'error');
            }
        });
    });
});
</script>



</body>
</html>

<?php include '../templates/footer.php'; ?>