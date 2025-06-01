<?php
require_once '../../config/db.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'estudiante') {
    header("Location: ../login/login.php");
    exit();
}

$db = new Database();
$conn = $db->connect();

$id_estudiante = $_SESSION['id'];

$stmt = $conn->prepare("
    SELECT n.valor_nota, n.comentarios, a.nombre_asignatura
    FROM nota n
    JOIN lista_participante lp ON n.id_lista = lp.id_lista
    JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
    JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
    WHERE lp.id_usuario_estudiante = ?
");
$stmt->execute([$id_estudiante]);
$notas = $stmt->fetchAll(PDO::FETCH_ASSOC);
include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mis Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_estudiante.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Mis Notas</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Asignatura</th>
                        <th>Nota</th>
                        <th>Comentarios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($notas as $n): ?>
                    <tr>
                        <td><?= $n['nombre_asignatura'] ?></td>
                        <td><?= $n['valor_nota'] ?></td>
                        <td><?= $n['comentarios'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>
</body>
</html>
