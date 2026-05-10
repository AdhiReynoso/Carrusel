<?php
include 'db.php';
$pdo = conectarPG();

$stmt = $pdo->query("SELECT id, nombre, ruta FROM imagenes ORDER BY id ASC");
$imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($imagenes);
?>
