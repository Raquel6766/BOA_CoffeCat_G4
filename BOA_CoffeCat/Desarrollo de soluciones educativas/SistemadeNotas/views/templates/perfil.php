<?php
session_start();
define('BASE_PATH', dirname(__DIR__));
if (!isset($_SESSION['id'])) {
    header("Location: ../views/login/login.php");
    exit();
}

require_once __DIR__ . '/header.php';
require_once BASE_PATH . '../../models/Usuario.php';

// Mostrar sidebar según rol
if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 'admin':
            require_once __DIR__ . '/sidebar_admin.php';
            break;
        case 'docente':
            require_once __DIR__ . '/sidebar_docente.php';
            break;
        case 'estudiante':
            require_once __DIR__ . '/sidebar_estudiante.php';
            break;
        default:
            // Opcional: sidebar por defecto o nada
            break;
    }
}

$usuarioModel = new Usuario();
$usuario = $usuarioModel->obtenerUsuarioPorId($_SESSION['id']);

if (!$usuario) {
    echo "<p>Usuario no encontrado.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil de Usuario</title>
    <!-- Bootstrap CSS CDN si no está en header.php -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar ya incluido arriba -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <div id="page-content-wrapper" class="w-100">
            <div class="container-fluid mt-4">
                <h1 class="mb-4">Perfil de Usuario</h1>
                <div class="card shadow-sm mx-auto" style="max-width: 600px;">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Información Personal</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre completo:</strong> 
                            <?=
                                htmlspecialchars(
                                    trim($usuario['primer_nombre'] . ' ' . 
                                         ($usuario['segundo_nombre'] ? $usuario['segundo_nombre'] . ' ' : '') . 
                                         $usuario['primer_apellido'] . ' ' . 
                                         ($usuario['segundo_apellido'] ?? '')
                                    )
                                ); 
                            ?>
                        </p>
                        <p><strong>Nombre de usuario:</strong> <?= htmlspecialchars($usuario['nombre_usuario']) ?></p>
                        <p><strong>Correo electrónico:</strong> <?= htmlspecialchars($usuario['correo'] ?? 'No registrado') ?></p>
                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono'] ?? 'No registrado') ?></p>
                    </div>
                    <div class="card-footer text-end">
                        <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
                    </div>
                </div>
            </div>
        </div>        
        </main>
        <!-- Página principal -->

    </div>

    <!-- Bootstrap JS Bundle CDN si no está en footer.php -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Incluir footer común si tienes (scripts, cierre de body o html)
require_once __DIR__ . '/footer.php';
?>
