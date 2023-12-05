<?php
/*  En el archivo bd.php se creará la conexión a la base de datos y todas las funciones necesarias
    De esta forma tengo toda la lógica principal con la base de datos en este archivo y tengo un mejor control en funciones
    También de esta forma para manejar la sesión y persistencia lo único que debo añadir en los demás archivos y páginas solo sería
    la sesión, esto me genera mucha limpieza de código.
*/
$host = 'localhost:3308'; // Puede ser 'localhost' si la base de datos está en el mismo servidor || 127.0.0.1:3308
$dbname = 'proyectoexamen';
$username = 'root';
$password = '';


try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<p class="success">BD Conectada</p>';
} catch (PDOException $e) {
    // echo "Error de conexión: " . $e->getMessage();
    echo '<p class="error">Error de conexión</p>';
}
