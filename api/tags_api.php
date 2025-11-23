<?php
/*header('Content-Type: application/json');

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

echo json_encode($response);*/

header("Content-Type: application/json");
require_once __DIR__ . "/database.php";

$banco = new Banco();
$db = $banco->getConexao();

// Pega o método HTTP
$method = $_SERVER["REQUEST_METHOD"];

// Pega o ID da URL ou da query string
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", trim($path, "/"));
$id = end($parts);
$id = is_numeric($id) ? intval($id) : null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

// Pega JSON enviado
$input = json_decode(file_get_contents("php://input"), true) ?? [];

// ============================
// GET → Listar ou buscar
// ============================
if ($method === "GET") {

    if ($id) {
        // Buscar tag por ID
        $query = $db->prepare("SELECT * FROM tb_tags WHERE idTags = ?");
        $query->execute([$id]);
        echo json_encode($query->fetch(PDO::FETCH_ASSOC));
        exit;
    }

    // Listar todas as tags
    $query = $db->query("SELECT * FROM tb_tags");
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ============================
// POST → Adicionar
// ============================
if ($method === "POST") {

    $nomeTag = $input['nomeTag'] ?? '';

    if (!$nomeTag) {
        echo json_encode(["erro" => "Campo nomeTag é obrigatório"]);
        exit;
    }

    $query = $db->prepare("INSERT INTO tb_tags (nomeTag) VALUES (?)");
    $query->execute([$nomeTag]);

    echo json_encode(["status" => "ok", "idNovo" => $db->lastInsertId()]);
    exit;
}

// ============================
// PUT → Atualizar
// ============================
if ($method === "PUT") {

    if (!$id) {
        echo json_encode(["erro" => "ID não informado"]);
        exit;
    }

    $nomeTag = $input['nomeTag'] ?? '';

    $query = $db->prepare("UPDATE tb_tags SET nomeTag = ? WHERE idTags = ?");
    $query->execute([$nomeTag, $id]);

    echo json_encode(["status" => "atualizado"]);
    exit;
}

// ============================
// DELETE → Deletar
// ============================
if ($method === "DELETE") {

    if (!$id) {
        echo json_encode(["erro" => "ID não informado"]);
        exit;
    }

    $query = $db->prepare("DELETE FROM tb_tags WHERE idTags = ?");
    $query->execute([$id]);

    echo json_encode(["status" => "deletado"]);
    exit;
}

echo json_encode(["erro" => "Método não permitido"]);

