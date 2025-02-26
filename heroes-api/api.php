<?php
// Habilitar CORS
header("Access-Control-Allow-Origin: http://localhost:4200"); // Permite solicitudes desde este origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cabeceras permitidas
header("Content-Type: application/json; charset=UTF-8");


// Obtener la URL procesada
$url = isset($_GET['url']) ? $_GET['url'] : '';
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Separar la URL en segmentos
$urlParts = explode('/', $url);
$resource = $urlParts[0];  // "heroes"
$id = isset($urlParts[1]) ? $urlParts[1] : null;  // "1" si existe

// Pasar el id a heroes.php
$_GET['id'] = $id; // Pasar el ID por GET

switch ($resource) {
    case 'heroes':
        require 'heroes.php';  // Incluir el archivo que maneja héroes
        break;
    default:
        echo json_encode(["error" => "Endpoint no encontrado"]);
        break;
}
?>

