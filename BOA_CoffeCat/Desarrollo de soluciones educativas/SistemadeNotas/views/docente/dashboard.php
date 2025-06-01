<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}
include '../templates/header.php';
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">   
    <style>
        .main {
            margin-left: 240px; /* Ajusta dependiendo del ancho del sidebar */
        }
    </style> 
</head>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_docente.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h2>Panel del Docente</h2>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Cursos</h5>
                            <p class="card-text">cursos asignados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Asignaturas</h5>
                            <p class="card-text">Ver asignaturas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Notas</h5>
                            <p class="card-text">Agregar, editar y eliminar notas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Estudiantes</h5>
                            <p class="card-text">Buscar estudiantes y asignaciones</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
