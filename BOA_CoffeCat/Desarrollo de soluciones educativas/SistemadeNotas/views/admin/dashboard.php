<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login/login.php");
    exit();
}
include '../templates/header.php';
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Asegura espacio a la izquierda y abajo del header */
        main {
            margin-left: 220px; /* Ancho del sidebar */
            padding-top: 100px; /* Altura del navbar + líneas decorativas */
        }

        /* Estilos del sidebar ya fijos */
        .sidebar_admin {
            position: fixed;
            top: 64px; /* 56px navbar + 8px líneas */
            left: 0;
            height: calc(100vh - 64px);
            width: 220px;
            background-color: rgb(11, 12, 66);
            z-index: 998;
        }

    </style>    
</head>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_admin.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h2>Panel del Administrador</h2>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Usuarios</h5>
                            <p class="card-text">Gestionar docentes y estudiantes</p>
                            <a href="usuarios.php" class="btn btn-light btn-sm mt-2">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Cursos</h5>
                            <p class="card-text">Administrar cursos disponibles</p>
                            <a href="cursos_admin.php" class="btn btn-light btn-sm mt-2">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Asignaturas</h5>
                            <p class="card-text">Asignar materias a docentes</p>
                            <a href="asignaturas_admin.php" class="btn btn-light btn-sm mt-2">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Matrículas</h5>
                            <p class="card-text">Inscribir estudiantes en asignaturas</p>
                            <a href="matricula.php" class="btn btn-light btn-sm mt-2">Ver más</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Notas</h5>
                            <p class="card-text">Revisar todas las notas</p>
                            <a href="notas_admin.php" class="btn btn-light btn-sm mt-2">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Reportes</h5>
                            <p class="card-text">Generar reportes por curso o estudiante</p>
                            <a href="reportes.php" class="btn btn-light btn-sm mt-2">Ver más</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
