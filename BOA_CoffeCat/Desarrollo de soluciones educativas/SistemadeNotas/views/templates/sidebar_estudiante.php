<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Estudiante</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .sidebar_estudiante {
      position: fixed;
      top: 64px; /* Ajusta según el navbar */
      left: 0;
      height: calc(100vh - 64px);
      width: 220px;
      background-color: rgb(11, 12, 66);
      z-index: 998;
      padding-top: 1rem;
      overflow-y: auto;
      transition: transform 0.3s ease;
    }

    .menu-toggle {
      display: none;
      position: fixed;
      top: 15px;
      left: 0;
      width: 40px;
      height: 40px;
      z-index: 9999;
      background-color: rgb(11, 12, 66);
      border: none;
      color: white;
      font-size: 24px;
      padding: 0;
      cursor: pointer;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background-color: rgba(0,0,0,0.5);
      z-index: 997;
    }

    @media (min-width: 769px) {
      .sidebar_estudiante {
        transform: translateX(0) !important;
      }

      .menu-toggle,
      .overlay {
        display: none !important;
      }
    }

    @media (max-width: 768px) {
      .sidebar_estudiante {
        top: 0;
        height: 100vh;
        transform: translateX(-100%);
      }

      .sidebar_estudiante.active {
        transform: translateX(0);
      }

      .menu-toggle {
        display: block;
      }

      .overlay.active {
        display: block;
      }
    }
  </style>
</head>

<body>
  <!-- Botón hamburguesa -->
  <button class="menu-toggle" aria-label="Abrir menú">&#9776;</button>

  <!-- Sidebar estudiante -->
  <div class="sidebar_estudiante d-flex flex-column flex-shrink-0 p-3 text-white">
    <div class="text-center mb-3">
      <img src="../../images/xd.png" class="img-fluid" width="100">
      <h5 class="mt-2">Estudiante</h5>
    </div>

    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li><a href="../estudiante/dashboard.php" class="nav-link text-white">Dashboard</a></li>
      <li><a href="../estudiante/mis_notas.php" class="nav-link text-white">Mis Notas</a></li>
    </ul>
  </div>

  <!-- Overlay -->
  <div class="overlay"></div>

  <script>
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar_estudiante');
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

