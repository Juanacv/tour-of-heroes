<?php
require 'db.php';  // Conexión a la base de datos

$requestMethod = $_SERVER['REQUEST_METHOD'];
$inputData = json_decode(file_get_contents("php://input"), true);

// Comprobar si el ID es pasado en la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($requestMethod) {
    case 'GET':
        getHeroes($id);  // Llamar la función con el ID si es necesario
        break;
    case 'POST':
        addHero($inputData);
        break;
    case 'PUT':
        updateHero($inputData);
        break;
    case 'DELETE':
        deleteHero($id);
        break;
    default:
        echo json_encode(["error" => "Método no permitido"]);
}

function getHeroes($id = null) {
  global $conn;

  // Verificar si se ha proporcionado un parámetro "name" en la URL
  $name = isset($_GET['name']) ? $_GET['name'] : null;

  if ($id) {
    // Obtener un héroe específico por ID
    $stmt = $conn->prepare("SELECT * FROM heroes WHERE id = ? ORDER BY heroscore DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $hero = $result->fetch_assoc();

    if ($hero) {
      echo json_encode($hero);
    } else {
      echo json_encode(["error" => "Heroe no encontrado"]);
    }
  } elseif ($name) {
    // Buscar héroes por nombre
    $searchTerm = "%$name%"; // Usar comodines para buscar coincidencias parciales
    $stmt = $conn->prepare("SELECT * FROM heroes WHERE name LIKE ? ORDER BY heroscore DESC");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $heroes = $result->fetch_all(MYSQLI_ASSOC);

    if ($heroes) {
      echo json_encode($heroes);
    } else {
      echo json_encode(["error" => "No se encontraron héroes con ese nombre"]);
    }
  } else {
    // Obtener todos los héroes
    $result = $conn->query("SELECT * FROM heroes ORDER BY heroscore DESC");
    $heroes = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($heroes);
  }
}

function addHero($data) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO heroes (name, alterego, superpower, heroscore) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $data['name'], $data['alterego'], $data['superpower'], $data['heroscore']);
    $stmt->execute();
    echo json_encode(["message" => "Hero added"]);
}

function updateHero($data) {
    global $conn;
	// Depuración: Verifica los datos recibidos
	error_log(print_r($data, true));	
    $stmt = $conn->prepare("UPDATE heroes SET name=?, alterego=?, superpower=?, heroscore=? WHERE id=?");
    $stmt->bind_param("sssii", $data['name'], $data['alterego'], $data['superpower'], $data['heroscore'], $data['id']);
    $stmt->execute();
    echo json_encode(["message" => "Hero updated"]);
}

function deleteHero($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM heroes WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(["message" => "Hero deleted"]);
}
?>
