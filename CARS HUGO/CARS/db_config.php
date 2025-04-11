<?php

define('DB_HOST', 'localhost:3307');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cars_db');

try {
    // Conexión sin base de datos para crearla si no existe
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la base de datos si no existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);

    // Conectar a la base de datos creada
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
