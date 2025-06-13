<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login/login.php");
    exit();
}

require_once '../../models/Nota.php';
require_once '../../config/db.php';

$notaObj = new Nota();
$notas = $notaObj->obtenerTodasLasNotas();

// Obtener todos los estudiantes y asignaturas
$db = new Database();
$conn = $db->connect();

$listas = $conn->query("
    SELECT lp.id_lista, u.nombre_usuario AS estudiante, a.nombre_asignatura
    FROM lista_participante lp
    JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
    JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
    JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
")->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Notas (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"/>
    <style>
        .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); justify-content: center; align-items: center; }
        .modal-contenido { background: #fff; padding: 20px; border-radius: 8px; max-width: 700px; width: 90%; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_admin.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Gestión de Notas (Administrador)</h3>

            <button id="btnAbrirModal" class="btn mb-3" style="background-color:rgb(35, 100, 38); color:white;">Agregar Nota</button>

            <!-- Modal -->
            <div id="modalFormulario" class="modal">
                <div class="modal-contenido">
                    <h5>Agregar Nota</h5>
                    <form id="formAgregarNota" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <input type="number" name="valor_nota" class="form-control" placeholder="Nota" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="comentarios" class="form-control" placeholder="Comentarios">
                        </div>
                        <div class="col-md-5">
                            <select name="id_lista" class="form-select" required>
                                <option value="">Estudiante / Asignatura</option>
                                <?php foreach ($listas as $l): ?>
                                    <option value="<?= $l['id_lista'] ?>"><?= $l['estudiante'] ?> - <?= $l['nombre_asignatura'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-success me-2">Agregar</button>
                            <button type="button" id="btnCerrarModal" class="btn btn-secondary">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

            <table id="tablaNotas" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Asignatura</th>
                        <th>Nota</th>
                        <th>Comentarios</th>
                        <th>Guardar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notas as $n): ?>
                        <tr>
                            <td><?= $n['estudiante'] ?></td>
                            <td><?= $n['asignatura'] ?></td>
                            <td><input type="number" value="<?= $n['valor_nota'] ?>" class="form-control input-nota" data-id="<?= $n['id_nota'] ?>"></td>
                            <td><input type="text" value="<?= $n['comentarios'] ?>" class="form-control input-comentarios" data-id="<?= $n['id_nota'] ?>"></td>
                            <td><button class="btn btn-primary btn-sm btn-guardar" data-id="<?= $n['id_nota'] ?>">Guardar</button></td>
                            <td><button class="btn btn-danger btn-sm btn-eliminar" data-id="<?= $n['id_nota'] ?>">Eliminar</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    const tabla = $('#tablaNotas').DataTable({ language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" } });

    $('#btnAbrirModal').on('click', () => $('#modalFormulario').css('display', 'flex'));
    $('#btnCerrarModal').on('click', () => $('#modalFormulario').hide());

    $('#formAgregarNota').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'agregar');

        fetch('../../controllers/NotaController.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            Swal.fire(data.success ? 'Agregado' : 'Error', data.message, data.success ? 'success' : 'error');
            if (data.success) location.reload();
        });
    });

    $('.btn-guardar').on('click', function() {
        const id = $(this).data('id');
        const fila = $(this).closest('tr');
        const valor_nota = fila.find('.input-nota').val();
        const comentarios = fila.find('.input-comentarios').val();
        fetch('../../controllers/NotaController.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({ action: 'editar', id, valor_nota, comentarios })
        }).then(res => res.json()).then(data => {
            Swal.fire(data.success ? 'Guardado' : 'Error', data.message, data.success ? 'success' : 'error');
        });
    });

    $('.btn-eliminar').on('click', function() {
        const id = $(this).data('id');
        const fila = $(this).closest('tr');
        Swal.fire({
            title: '¿Eliminar nota?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) {
                fetch('../../controllers/NotaController.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({ action: 'eliminar', id })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        Swal.fire('Eliminada', data.message, 'success');
                        tabla.row(fila).remove().draw();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>
<?php include '../templates/footer.php'; ?>
