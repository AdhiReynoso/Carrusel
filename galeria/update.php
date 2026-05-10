<?php
require 'db.php';
header('Content-Type: application/json');

if (isset($_FILES['imagen']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $pdo->prepare("SELECT ruta FROM imagenes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $anterior = $stmt->fetch();

    $archivo = $_FILES['imagen'];
    $nombre  = basename($archivo['name']);
    $ext     = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
    $permitidos = ['jpg','jpeg','png','gif','webp'];

    if (!in_array($ext, $permitidos)) {
        echo json_encode(['error' => 'Tipo no permitido']); exit;
    }

    $nombre_unico = uniqid() . "_" . $nombre;
    $ruta = "uploads/" . $nombre_unico;

    if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
        if ($anterior && file_exists($anterior['ruta'])) unlink($anterior['ruta']);
        $pdo->prepare("UPDATE imagenes SET nombre=:nombre, ruta=:ruta WHERE id=:id")
            ->execute([':nombre' => $nombre, ':ruta' => $ruta, ':id' => $id]);
        echo json_encode(['success' => true, 'ruta' => $ruta, 'nombre' => $nombre]);
    } else {
        echo json_encode(['error' => 'Error al subir']);
    }
}
?>
