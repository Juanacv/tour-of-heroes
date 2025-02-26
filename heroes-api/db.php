<?php
$host = "localhost";  // O la IP del servidor de la base de datos
$user = "root";       // Usuario de MySQL
$pass = "root";       // Contraseña de MySQL (déjala vacía si no usaste una)
$dbname = "heroes";   // Nombre de la base de datos

$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// Configurar el juego de caracteres a UTF-8
$conn->set_charset("utf8");
?>
