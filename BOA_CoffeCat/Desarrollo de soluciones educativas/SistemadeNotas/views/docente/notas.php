<?php
require_once '../../models/Nota.php';
require_once '../../models/Asignatura.php';
require_once '../../models/Curso.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}

$notaObj = new Nota();
$id_docente = $_SESSION['id'];
$notas = $notaObj->obtenerNotasPorDocente($id_docente);

// Conexión directa para obtener estudiantes/asignaturas solo del docente actual
$db = new Database();
$conn = $db->connect();

$listas = $conn->prepare("
    SELECT lp.id_lista, u.nombre_usuario AS estudiante, a.nombre_asignatura
    FROM lista_participante lp
    JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
    JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
    JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
    WHERE ad.id_usuario_docente = ?
");
$listas->execute([$id_docente]);
$listas = $listas->fetchAll(PDO::FETCH_ASSOC);

$cursos = $conn->query("SELECT id_curso, grado FROM curso")->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"/>
    <style>
        .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); justify-content: center; align-items: center; }
        .modal-contenido { background-color: #fff; padding: 20px 30px; border-radius: 8px; max-width: 700px; width: 90%; box-shadow: 0 5px 15px rgba(0,0,0,0.3); display: flex; flex-wrap: wrap; gap: 10px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_docente.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Gestión de Notas</h3>
            <!-- Botón para abrir el modal -->
            <button id="btnAbrirModal" class="btn mb-3" style="background-color:rgb(35, 100, 38); color: white">Agregar Nota</button>

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
                            <?php foreach($listas as $l): ?>
                                <option value="<?= $l['id_lista'] ?>">
                                    <?= $l['estudiante'] ?> - <?= $l['nombre_asignatura'] ?>
                                </option>
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
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($notas as $n): ?>
                    <tr>
                        <td><?= $n['estudiante'] ?></td>
                        <td><?= $n['nombre_asignatura'] ?></td>
                        <td>
                            <input type="number" value="<?= $n['valor_nota'] ?>" class="form-control input-nota" data-id="<?= $n['id_nota'] ?>">
                        </td>
                        <td>
                            <input type="text" value="<?= $n['comentarios'] ?>" class="form-control input-comentarios" data-id="<?= $n['id_nota'] ?>">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm btn-guardar" data-id="<?= $n['id_nota'] ?>">Guardar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>
<!-- DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
let tablaNotas;
$(document).ready(function() {
    tablaNotas = $('#tablaNotas').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" },
        columns: [
            { title: "Estudiante" },
            { title: "Asignatura" },
            { title: "Nota" },
            { title: "Comentarios" },
            { title: "Guardar" },
            { title: "Eliminar" }
        ]
    });


    // Guardar nota AJAX
    $('#tablaNotas').on('click', '.btn-guardar', function() {
        const id = $(this).data('id');
        const fila = $(this).closest('tr');
        const valor_nota = fila.find('.input-nota').val();
        const comentarios = fila.find('.input-comentarios').val();
        fetch('../../controllers/NotaController.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'editar',
                id: id,
                valor_nota: valor_nota,
                comentarios: comentarios,
                curso: curso
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                Swal.fire('¡Éxito!', data.message, 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        }).catch(() => {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        });
    });

});

// Modal scripts
const btnAbrirModal = document.getElementById('btnAbrirModal');
const modalFormulario = document.getElementById('modalFormulario');
const btnCerrarModal = document.getElementById('btnCerrarModal');
btnAbrirModal.addEventListener('click', () => { modalFormulario.style.display = 'flex'; });
btnCerrarModal.addEventListener('click', () => { modalFormulario.style.display = 'none'; });
window.addEventListener('click', (e) => { if (e.target === modalFormulario) { modalFormulario.style.display = 'none'; }});

// Agregar Nota AJAX
document.getElementById('formAgregarNota').addEventListener('submit', function(e){
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    formData.append('action', 'agregar');
    fetch('../../controllers/NotaController.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            Swal.fire('¡Éxito!', data.message, 'success');
            if(data.nota){
                tablaNotas.row.add([
                    data.nota.estudiante,
                    data.nota.asignatura,
                    `<input type="number" value="${data.nota.valor_nota}" class="form-control input-nota" data-id="${data.nota.id_nota}">`,
                    `<input type="text" value="${data.nota.comentarios}" class="form-control input-comentarios" data-id="${data.nota.id_nota}">`,
                    `<button class="btn btn-primary btn-sm btn-guardar" data-id="${data.nota.id_nota}">Guardar</button>`,
                    `<button class="btn btn-danger btn-sm btn-eliminar" data-id="${data.nota.id_nota}">Eliminar</button>`
                ]).draw(false);
            }
            modalFormulario.style.display = 'none';
            form.reset();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(() => {
        Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
    });
});
</script>
</body>
</html>
<?php include '../templates/footer.php'; ?>