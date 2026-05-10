<?php
require 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id']);

$stmt = $pdo->prepare("SELECT ruta FROM imagenes WHERE id = :id");
$stmt->execute([':id' => $id]);
$img = $stmt->fetch();

if ($img) {
    if (file_exists($img['ruta'])) unlink($img['ruta']);
    $pdo->prepare("DELETE FROM imagenes WHERE id = :id")->execute([':id' => $id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Imagen no encontrada']);
}
?>
