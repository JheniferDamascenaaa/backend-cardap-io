<?php
// Configurações e cabeçalhos
header('Content-Type: application/json');

require_once '../config/database.php';

require_once '../controllers/restaurante_controller.php';

// Instancia a conexão e o controller
$database = new Database();
$db = $database->conectar();
$restauranteController = new RestauranteController($db);

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$response = [];

switch($action) {
    
    case 'listar':
        $response = $restauranteController->listar();
        break;

    case 'buscar':
        $id = $_GET['idRestaurante'] ?? $_POST['idRestaurante'] ?? null;
        if($id) {
            $response = $restauranteController->buscarPorId($id);
        } else {
            $response = ['error' => 'Parâmetro idRestaurante não fornecido'];
        }
        break;

    case 'adicionar':
        $dados = [
            'nomeRestaurante' => $_POST['nomeRestaurante'] ?? null,
            'endereco' => $_POST['endereco'] ?? null,
            'idTags' => $_POST['idTags'] ?? null,
            'notaMedia' => $_POST['notaMedia'] ?? null,
            'descricao' => $_POST['descricao'] ?? null
        ];
        $response = $restauranteController->adicionar($dados);
        break;

    case 'atualizar':
        $id = $_POST['idRestaurante'] ?? null;
        $dados = [
            'nomeRestaurante' => $_POST['nomeRestaurante'] ?? null,
            'endereco' => $_POST['endereco'] ?? null,
            'idTags' => $_POST['idTags'] ?? null,
            'notaMedia' => $_POST['notaMedia'] ?? null,
            'descricao' => $_POST['descricao'] ?? null
        ];
        if($id) {
            $response = $restauranteController->atualizar($id, $dados);
        } else {
            $response = ['error' => 'Parâmetro idRestaurante não fornecido'];
        }
        break;

    case 'deletar':
        $id = $_POST['idRestaurante'] ?? $_GET['idRestaurante'] ?? null;
        if($id) {
            $response = $restauranteController->deletar($id);
        } else {
            $response = ['error' => 'Parâmetro idRestaurante não fornecido'];
        }
        break;

    default:
        $response = ['error' => 'Ação inválida ou não fornecida'];
}

echo json_encode($response);
