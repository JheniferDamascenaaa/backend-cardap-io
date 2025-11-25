<?php

header("Content-Type: application/json");

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../controllers/restaurante_controller.php';



$database = new Banco();
$banco = $database->getConexao();
$controller = new RestauranteController($banco);

$metodo = $_SERVER['REQUEST_METHOD'];


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($path, '/'));

// Obtém o último segmento da URL como ID, se for numérico
$id = end($parts);
$id = is_numeric($id) ? intval($id) : null;

// Lê JSON quando necessário (POST e PUT)
$input = json_decode(file_get_contents("php://input"), true) ?? [];

switch ($metodo) {

    case 'GET':
        if ($id) {
            echo json_encode($controller->buscarPorId($id));
        } else {
            echo json_encode($controller->listar());
        }
        break;

    case 'POST':
        echo json_encode($controller->adicionar($input));
        break;

    case 'PUT':
        if ($id) {
            echo json_encode($controller->atualizar($id, $input));
        } else {
            echo json_encode(['error' => 'ID não informado']);
        }
        break;

    case 'DELETE':
        if ($id) {
            echo json_encode($controller->deletar($id));
        } else {
            echo json_encode(['error' => 'ID não informado']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método HTTP não suportado']);
}  