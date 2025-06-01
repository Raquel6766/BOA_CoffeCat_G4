<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'estudiante') {
    header("Location: ../login/login.php");
    exit();
}
include '../templates/header.php';
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_estudiante.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h2>Bienvenido Estudiante</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Mis Cursos</h5>
                            <p class="card-text">Ver los cursos en los que estoy inscrito</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Mis Asignaturas</h5>
                            <p class="card-text">Asignaturas que estoy recibiendo</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Mis Notas</h5>
                            <p class="card-text">Consultar calificaciones por materia</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
