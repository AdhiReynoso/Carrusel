<?php
include 'db.php';
$db_mysql = conectarDB();
$db_pg    = conectarPG();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Obtener ruta del archivo físico
    $stmt = $db_mysql->prepare("SELECT ruta FROM imagenes WHERE id = ?");
    $stmt->execute([$id]);
    $foto = $stmt->fetch();

    if ($foto) {
        // Borrar archivo físico
        $ruta_absoluta = __DIR__ . '/' . $foto['ruta'];
        if (file_exists($ruta_absoluta)) unlink($ruta_absoluta);

        // DELETE en MariaDB
        $del = $db_mysql->prepare("DELETE FROM imagenes WHERE id = ?");
        $del->execute([$id]);

        // DELETE en PostgreSQL
        $del = $db_pg->prepare("DELETE FROM imagenes WHERE id = ?");
        $del->execute([$id]);

        echo json_encode(['ok' => true, 'mensaje' => 'Eliminado en ambas bases de datos']);
    } else {
        echo json_encode(['ok' => false, 'mensaje' => 'Imagen no encontrada']);
    }
} else {
    echo json_encode(['ok' => false, 'mensaje' => 'ID no recibido']);
}
?>
