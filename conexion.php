<?php

// Datos de conexión
$servidor = "localhost";
$baseDatos = "TIENDA";
$usuario = "root";
$password = "";

try {

    // Crear la conexión
    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$baseDatos;charset=utf8",
        $usuario,
        $password
    );

    // Configurar el modo de errores
    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Configurar el modo de recuperación de datos
    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    die("Error de conexión: " . $e->getMessage());

}

?>