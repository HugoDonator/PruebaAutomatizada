<?php
require 'db_config.php';

try{

    $sql = "CREATE TABLE IF NOT EXISTS personajes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        color VARCHAR(50) NOT NULL,
        tipo VARCHAR(50) NOT NULL,
        nivel INT NOT NULL,
        foto VARCHAR(255) NOT NULL
    )";

    $pdo->exec($sql);
    echo "Tabla personajes creada con éxito.";
}
catch(PDOException $e){
    die("ERROR: No se pudo ejecutar $sql. " . $e->getMessage());
}
?>
