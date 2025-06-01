<head>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <style>
  .header-line-wrapper {
    position: fixed;
    top: 56px; /* Ajustado para no solaparse con el navbar */
    left: 0;
    width: 100%;
    display: flex;
    height: 8px; /* Altura de las líneas */
    z-index: 999; /* Altura de las líneas */
  }

  .header-line {
    flex: 1; /* Cada línea ocupa el 50% del ancho */
    height: 100%;
  }

  .line-left {
    background-color:rgb(143, 28, 39); /* Azul */
  }

  .line-right {
    background-color:rgb(16, 18, 122) /* Rojo */
  }
  body {
    padding-top: 100px; /* Ajustado a la altura combinada del navbar + líneas */
  }
</style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color:rgb(41, 41, 41);">
    <div class="container-fluid ">
      <a class="navbar-brand" href="#">Sistema Notas</a>

      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="perfilDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle fs-4 me-2"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="perfilDropdown">
          <li><a class="dropdown-item" href="#">Mi perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="../../controllers/LogoutController.php">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="header-line-wrapper">
    <div class="header-line line-left"></div>
    <div class="header-line line-right"></div>
  </div>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  
</body>

