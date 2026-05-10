<?php
// borrar.php
include 'db.php';
$id = $_POST['id'];
$con = conectarDB();

// 1. Opcional: Borrar el archivo físico de la carpeta img_carrusel
// (Aquí necesitarías una consulta previa para obtener la ruta)

// 2. Borrar el registro de MariaDB
$query = "DELETE FROM imagenes WHERE id = $id";
mysqli_query($con, $query);

echo "success";
?>
