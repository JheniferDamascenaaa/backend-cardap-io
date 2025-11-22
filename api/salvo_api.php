<?php
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../controllers/salvo_controller.php';

$database = new Database();
$db = $database->conectar();
$salvoController = new SalvoController($db);

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$response = [];

switch($action) {
    
    case 'listar':
        $idUsuario = $_GET['idUsuario'] ?? $_POST['idUsuario'] ?? null;
        if($idUsuario) {
            $response = $salvoController->listarPorUsuario($idUsuario);
        } else {
            $response = ['error' => 'Parâmetro idUsuario não fornecido'];
        }
        break;

    case 'buscar':
        $id = $_GET['idSalvo'] ?? $_POST['idSalvo'] ?? null;
        if($id) {
            $response = $salvoController->buscarPorId($id);
        } else {
            $response = ['error' => 'Parâmetro idSalvo não fornecido'];
        }
        break;

    case 'adicionar':
        $dados = [
            'idUsuario' => $_POST['idUsuario'] ?? null,
            'idRestaurante' => $_POST['idRestaurante'] ?? null,
            'notaRestaurante' => $_POST['notaRestaurante'] ?? null,
            'descricao' => $_POST['descricao'] ?? null
        ];
        $response = $salvoController->adicionar($dados);
        break;

    case 'atualizar':
        $id = $_POST['idSalvo'] ?? null;
        $dados = [
            'notaRestaurante' => $_POST['notaRestaurante'] ?? null,
            'descricao' => $_POST['descricao'] ?? null,
            'avaliado' => $_POST['avaliado'] ?? null
        ];
        if($id) {
            $response = $salvoController->atualizar($id, $dados);
        } else {
            $response = ['error' => 'Parâmetro idSalvo não fornecido'];
        }
        break;

    case 'deletar':
        $id = $_POST['idSalvo'] ?? $_GET['idSalvo'] ?? null;
        if($id) {
            $response = $salvoController->deletar($id);
        } else {
            $response = ['error' => 'Parâmetro idSalvo não fornecido'];
        }
        break;

    default:
        $response = ['error' => 'Ação inválida ou não fornecida'];
}

echo json_encode($response);
