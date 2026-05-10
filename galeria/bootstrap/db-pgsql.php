<?php
// db-pgsql.php

function conectarPostgres() {
    $host = "localhost";
    $port = "5432";
    $db   = "meztlitech";
    $user = "meztli"; // Usuario por defecto en Postgres
    $pass = "12345678";

    // El DSN cambia el prefijo a 'pgsql'
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    try {
        // Creamos la instancia de PDO
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        return $pdo;
    } catch (PDOException $e) {
        die("Error conectando a PostgreSQL: " . $e->getMessage());
    }
}
?>