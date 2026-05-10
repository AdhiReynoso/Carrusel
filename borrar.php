<?php
include 'db.php';
$pdo = conectarPG();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $pdo->prepare("SELECT ruta FROM imagenes WHERE id = ?");
    $stmt->execute([$id]);
    $foto = $stmt->fetch();

    if ($foto) {
        $ruta_absoluta = __DIR__ . '/' . $foto['ruta'];
        if (file_exists($ruta_absoluta)) unlink($ruta_absoluta);

        $del = $pdo->prepare("DELETE FROM imagenes WHERE id = ?");
        $del->execute([$id]);

        echo json_encode(['ok' => true, 'mensaje' => 'Eliminado correctamente']);
    } else {
        echo json_encode(['ok' => false, 'mensaje' => 'Imagen no encontrada']);
    }
} else {
    echo json_encode(['ok' => false, 'mensaje' => 'ID no recibido']);
}
?>
