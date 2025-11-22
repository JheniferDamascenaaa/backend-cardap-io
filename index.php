<?php
header("Content-Type: application/json");

// Incluindo controllers (caminho relativo usando __DIR__)
require_once realpath(__DIR__ . '/../controllers/RestaurantController.php');
require_once realpath(__DIR__ . '/../controllers/UserController.php');
require_once realpath(__DIR__ . '/../controllers/ReviewController.php');

$uri = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $uri);

$resource = $parts[0] ?? null;  
$id = $parts[1] ?? null;

// Roteamento
switch ($resource) {
    case 'restaurantes':
        $controller = new RestaurantController();
        break;
    case 'usuarios':
        $controller = new UserController();
        break;
    case 'avaliacoes':
        $controller = new ReviewController();
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Recurso não encontrado"]);
        exit;
}

// Roteamento por método HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id ? $controller->show($id) : $controller->index();
        break;
    case 'POST':
        $controller->store();
        break;
    case 'PUT':
        $id ? $controller->update($id) : http_response_code(400);
        break;
    case 'DELETE':
        $id ? $controller->destroy($id) : http_response_code(400);
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
}
