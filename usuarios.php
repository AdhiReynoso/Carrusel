<?php
session_start();
$_SESSION["username"] = "juan";
$_SESSION["login_time"] = time();

require_once 'db.php';

$nombre = $_POST['nombre'];
$email  = $_POST['email'];
$pwd    = $_POST['pwd'];

$db_mysql = conectarDB();

try {
    $passwordHash = password_hash($pwd, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";

    $query = $db_mysql->prepare($sql);
    $resultado = $query->execute([
        'nombre'   => $nombre,
        'email'    => $email,
        'password' => $passwordHash
    ]);

    if ($resultado) {
        echo "El usuario se ha almacenado correctamente! <a href='index.html'>Continuar</a>";
    }
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        echo "El email ya existe, favor de intentarlo con otro correo. <a href='registro.html'>Continuar</a>";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
