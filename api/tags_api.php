<?php
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../controllers/tags_controller.php';

$database = new Database();
$db = $database->conectar();
$tagsController = new TagsController($db);

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$response = [];

switch($action) {
    
    case 'listar':
        $response = $tagsController->listar();
        break;

    case 'buscar':
        $id = $_GET['idTags'] ?? $_POST['idTags'] ?? null;
        if($id) {
            $response = $tagsController->buscarPorId($id);
        } else {
            $response = ['error' => 'Parâmetro idTags não fornecido'];
        }
        break;

    default:
        $response = ['error' => 'Ação inválida ou não fornecida'];
}

echo json_encode($response);
