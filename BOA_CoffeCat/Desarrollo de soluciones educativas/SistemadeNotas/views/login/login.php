<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  
  <style>
    body {
        background-image: url('../../images/fonfo.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
      }

    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    .btn-custom {
      background-color: rgb(11, 12, 66);
      color: white;
    }
    .btn-custom:hover {
      background-color: rgb(30, 33, 99);
    }
  </style>
</head>

<body>
  <section class="vh-100 d-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
          <div class="card">
            <div class="row g-0">
              
              <!-- Imagen: visible solo en md en adelante -->
              <div class="col-md-6 d-none d-md-block">
                <img src="../../images/image1.jpg" alt="login" class="img-fluid h-100" style="border-radius: 1rem 0 0 1rem; object-fit: cover;" />
              </div>

              <!-- Formulario -->
              <div class="col-md-6 d-flex align-items-center">
                <div class="card-body p-4 p-md-5 text-black w-100">

                  <form action="../../controllers/LoginController.php" method="POST">

                    <div class="text-center mb-4">
                      <img src="../../images/xd.png" class="mb-2" width="80">
                      <h4 class="fw-bold">Instituto Miguel de Cervantes</h4>
                    </div>

                    <h5 class="fw-normal mb-3 text-center">Inicia sesión con tu cuenta</h5>

                    <div class="form-outline mb-3">
                      <label class="form-label" for="usuario">Usuario</label>
                      <input type="text" id="usuario" name="usuario" class="form-control form-control-lg" required />
                    </div>

                    <div class="form-outline mb-4">
                      <label class="form-label" for="contrasena">Contraseña</label>
                      <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg" required />
                    </div>

                    <div class="d-grid">
                      <button class="btn btn-custom btn-lg" type="submit">Ingresar</button>
                    </div>

                  </form>

                </div>
              </div>
            </div>
          </div> <!-- card -->
        </div>
      </div>
    </div>
  </section>

  <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const urlParams = new URLSearchParams(window.location.search);
  const error = urlParams.get('error');
  const intento = urlParams.get('intento');

  if (error === 'credenciales' && intento === '1') {
      Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Usuario o contraseña incorrectos.',
          confirmButtonColor: '#1f2a6d',
          confirmButtonText: 'OK',
          showClass: {
              popup: 'animate__animated animate__zoomIn'
          },
          hideClass: {
              popup: 'animate__animated animate__zoomOut'
          }
      });
  }

</script>

</body>

</html>
