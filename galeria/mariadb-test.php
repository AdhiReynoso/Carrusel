<?php
$host = 'localhost';
$db = 'db_areynoso';
$usuario = 'areynoso';
$pass = '1234';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $usuario, $pass, $opciones);
    echo "<h1>¡Conexión Exitosa!</h1>";
    echo "<p>PHP 8 está conectado con MariaDB</p>";
} catch (\PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
