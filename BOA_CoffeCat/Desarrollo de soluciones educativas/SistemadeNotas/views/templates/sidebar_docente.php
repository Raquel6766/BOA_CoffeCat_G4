<head>
    <style>
        .sidebar_docente {
            position: fixed;
            top: 64; /* Ajusta la distancia desde el navbar */
            left: 0;
            height: 100vh; /* Evita que se solape con el navbar */
            width: 220px;
            background-color: rgb(11, 12, 66);
            z-index: 998; /* Justo debajo del navbar y las líneas */
        }
    </style>
</head>

<div class="sidebar_docente d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 220px; height: 100vh; position: fixed; background-color:rgb(11, 12, 66);">
    <div class="text-center mb-3">
        <img src="../../images/xd.png" class="img-fluid" width="100">
        <h5 class="mt-2">Docente</h5>
    </div>

  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li><a href="../docente/dashboard.php" class="nav-link text-white">Dashboard</a></li>
    <li><a href="../docente/cursos.php" class="nav-link text-white">Cursos</a></li>
    <li><a href="../docente/asignaturas.php" class="nav-link text-white">Asignaturas</a></li>
    <li><a href="../docente/notas.php" class="nav-link text-white">Notas</a></li>
  </ul>
</div>
