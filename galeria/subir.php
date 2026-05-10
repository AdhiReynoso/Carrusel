<?php
require 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
    $archivo = $_FILES['imagen'];
    $nombre  = basename($archivo['name']);
    $ext     = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
    $permitidos = ['jpg','jpeg','png','gif','webp'];

    if (!in_array($ext, $permitidos)) {
        echo json_encode(['error' => 'Tipo de archivo no permitido']);
        exit;
    }

    $nombre_unico = uniqid() . "_" . $nombre;
    $ruta = "uploads/" . $nombre_unico;

    if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
        $stmt = $pdo->prepare("INSERT INTO imagenes (nombre, ruta) VALUES (:nombre, :ruta)");
        $stmt->execute([':nombre' => $nombre, ':ruta' => $ruta]);
        $id = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'id' => $id, 'ruta' => $ruta, 'nombre' => $nombre]);
    } else {
        echo json_encode(['error' => 'Error al mover el archivo']);
    }
}
?>
