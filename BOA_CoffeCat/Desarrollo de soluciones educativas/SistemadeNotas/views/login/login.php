<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
  <section class="vh-100" style="background-color:rgb(32, 36, 83);">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <img src="../../images/image1.jpg"
                  alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem; height: 100%; object-fit: cover;" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form action="../../controllers/LoginController.php" method="POST">

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <img src="../../images/xd.png" class="w-25 h-25">
                      <span class="h1 fw-bold mb-0">Instituto Miguel de Cervantes</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Inicia sesión con tu cuenta</h5>

                    <div class="form-outline mb-4">
                      <label class="form-label" for="usuario">Usuario</label>
                      <input type="text" id="usuario" name="usuario" class="form-control form-control-lg" required />
                    </div>

                    <div class="form-outline mb-4">
                      <label class="form-label" for="contrasena">Contraseña</label>
                      <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg" required />
                    </div>

                    <div class="pt-1 mb-4">
                      <button class="btn btn-dark btn-lg btn-block w-100" type="submit">Ingresar</button>
                    </div>

                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>