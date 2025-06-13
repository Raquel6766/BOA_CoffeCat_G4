<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .sidebar_admin {
      position: fixed;
      top: 64px;
      left: 0;
      height: calc(100vh - 64px);
      width: 220px;
      background-color: rgb(11, 12, 66);
      z-index: 998;
      padding-top: 1rem;
      overflow-y: auto;
      transition: transform 0.3s ease;
    }

    .content {
      margin-left: 220px;
      padding: 1rem;
      transition: margin-left 0.3s ease;
    }

    .menu-toggle {
      display: none;
      position: fixed;
      top: 15px;
      left: 0; /* pegado al borde izquierdo */
      z-index: 9999;
      background-color: rgb(11, 12, 66);
      border: none;
      color: white;
      font-size: 24px;
      width: 40px;      /* ancho fijo */
      height: 40px;     /* alto fijo */
      padding: 0;       /* sin padding */
      cursor: pointer;
      border-radius: 0 4px 4px 0; /* redondeado solo derecha */
      display: flex;
      align-items: center;
      justify-content: center;
      transition: left 0.3s ease;
    }

    /* Cuando el sidebar está activo, movemos el botón a la derecha del sidebar */
    .sidebar_admin.active ~ .menu-toggle {
      left: 228px; /* 220px sidebar + 8px de separación */
    }

    .overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background-color: rgba(0,0,0,0.5);
      z-index: 997;
    }

    /* Desktop */
    @media (min-width: 769px) {
      .sidebar_admin {
        transform: translateX(0) !important;
      }
      .menu-toggle {
        display: none !important;
      }
      .overlay {
        display: none !important;
      }
      .content {
        margin-left: 220px;
      }
    }

    /* Mobile */
    @media (max-width: 768px) {
      .sidebar_admin {
        top: 0;
        height: 100vh;
        transform: translateX(-100%);
      }

      .sidebar_admin.active {
        transform: translateX(0);
      }

      .menu-toggle {
        display: flex;
      }

      .content {
        margin-left: 0;
      }

      .overlay.active {
        display: block;
      }
    }
  </style>
</head>
<body>
  <button class="menu-toggle" aria-label="Abrir menú">&#9776;</button>

  <div class="sidebar_admin">
    <div class="text-center mb-3" style="color:white;">
      <img src="../../images/xd.png" class="img-fluid" width="100" alt="Logo" />
      <h5 class="mt-2">Administrador</h5>
    </div>
    <hr style="border-color: rgba(255,255,255,0.3)" />
    <ul class="nav nav-pills flex-column mb-auto" style="list-style:none; padding-left: 0;">
      <li><a href="../admin/dashboard.php" class="nav-link text-white" style="display:block; padding:8px;">Dashboard</a></li>
      <li><a href="../admin/usuarios.php" class="nav-link text-white" style="display:block; padding:8px;">Usuarios</a></li>
      <li><a href="../admin/cursos_admin.php" class="nav-link text-white" style="display:block; padding:8px;">Cursos</a></li>
      <li><a href="../admin/asignaturas_admin.php" class="nav-link text-white" style="display:block; padding:8px;">Asignaturas</a></li>
      <li><a href="../admin/matricula.php" class="nav-link text-white" style="display:block; padding:8px;">Matrículas</a></li>
      <li><a href="../admin/notas_admin.php" class="nav-link text-white" style="display:block; padding:8px;">Notas</a></li>
    </ul>
  </div>

  <div class="overlay"></div>

  <script>
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar_admin');
    const overlay = document.querySelector('.overlay');

    menuToggle.addEventListener('click', () => {
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
    });
  </script>
</body>
</html>
