<?php
// db.php

function conectarDB() {
    $dsn = "mysql:host=localhost;dbname=db_areynoso;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, 'areynoso', '1234', $options);
    } catch (\PDOException $e) {
        die("Error MariaDB: " . $e->getMessage());
    }
}

function conectarPG() {
    $dsn = "pgsql:host=localhost;dbname=db_areynoso";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, 'areynoso', '1234', $options);
    } catch (\PDOException $e) {
        die("Error PostgreSQL: " . $e->getMessage());
    }
}
?>
