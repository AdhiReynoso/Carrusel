<?php
include 'db.php';
$db_mysql = conectarDB();
$db_pg    = conectarPG();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen'])) {
    $nombre_vis = trim($_POST['nombre_foto']);
    $nombre_arc = time() . "_" . basename($_FILES['imagen']['name']);
    $ruta_final = "img_carrusel/" . $nombre_arc;
    $carpeta    = __DIR__ . "/img_carrusel/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0755, true);
    }

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta . $nombre_arc)) {

        if (isset($_POST['id_actualizar']) && !empty($_POST['id_actualizar'])) {
            // ✅ MODO ACTUALIZAR en ambas BD
            $id = intval($_POST['id_actualizar']);

            // Borrar imagen física anterior (solo necesitas consultarla una vez)
            $stmt = $db_mysql->prepare("SELECT ruta FROM imagenes WHERE id = ?");
            $stmt->execute([$id]);
            $vieja = $stmt->fetch();
            if ($vieja) {
                $ruta_vieja = __DIR__ . '/' . $vieja['ruta'];
                if (file_exists($ruta_vieja)) unlink($ruta_vieja);
            }

            // UPDATE MariaDB
            $stmt = $db_mysql->prepare("UPDATE imagenes SET nombre = :nombre, ruta = :ruta WHERE id = :id");
            $stmt->execute(['nombre' => $nombre_vis, 'ruta' => $ruta_final, 'id' => $id]);

            // UPDATE PostgreSQL
            $stmt = $db_pg->prepare("UPDATE imagenes SET nombre = :nombre, ruta = :ruta WHERE id = :id");
            $stmt->execute(['nombre' => $nombre_vis, 'ruta' => $ruta_final, 'id' => $id]);

        } else {
            // ✅ MODO INSERTAR en ambas BD
            $sql  = "INSERT INTO imagenes (nombre, ruta) VALUES (:nombre, :ruta)";

            $stmt = $db_mysql->prepare($sql);
            $stmt->execute(['nombre' => $nombre_vis, 'ruta' => $ruta_final]);

            $stmt = $db_pg->prepare($sql);
            $stmt->execute(['nombre' => $nombre_vis, 'ruta' => $ruta_final]);
        }
    }
}

header("Location: index.php");
exit();
?>
