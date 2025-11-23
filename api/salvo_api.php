<?php
header("Content-Type: application/json");
require_once __DIR__ . "/database.php";

$banco = new Banco();
$db = $banco->getConexao();

// Método HTTP
$method = $_SERVER["REQUEST_METHOD"];

// URL → pegar ID se existir
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", trim($path, "/"));
$id = end($parts);
$id = is_numeric($id) ? intval($id) : null;

// JSON recebido
$input = json_decode(file_get_contents("php://input"), true) ?? [];

// ============================
// GET → LISTAR OU BUSCAR
// ============================
if ($method === "GET") {

    // /salvo_api.php?usuario=1
    if (isset($_GET["usuario"])) {
        $query = $db->prepare("SELECT * FROM tb_salvo WHERE idUsuario = ?");
        $query->execute([$_GET["usuario"]]);
        echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    // /salvo_api.php/5  → buscar por ID
    if ($id) {
        $query = $db->prepare("SELECT * FROM tb_salvo WHERE idSalvo = ?");
        $query->execute([$id]);
        echo json_encode($query->fetch(PDO::FETCH_ASSOC));
        exit;
    }

    echo json_encode(["erro" => "Use ?usuario=ID ou /salvo_api.php/ID"]);
    exit;
}

// ============================
// POST → ADICIONAR
// ============================
if ($method === "POST") {
    $query = $db->prepare("
    INSERT INTO tb_salvo (idUsuario, idRestaurante, notaRestaurante, avaliado, descricao)
    VALUES (?, ?, ?, ?, ?)
    ");

    $query->execute([
        $input["idUsuario"],
        $input["idRestaurante"],
        $input["notaRestaurante"],
        $input["avaliado"],    // adicionar aqui
        $input["descricao"]
    ]);


    echo json_encode(["status" => "ok", "idNovo" => $db->lastInsertId()]);
    exit;
}

// ============================
// PUT → ATUALIZAR
// ============================
if ($method === "PUT") {

    if (!$id) {
        echo json_encode(["erro" => "ID não informado"]);
        exit;
    }

    // Pega os valores do JSON ou define padrão
    $nota = isset($input["notaRestaurante"]) ? $input["notaRestaurante"] : null;
    $descricao = isset($input["descricao"]) ? $input["descricao"] : "";
    $avaliado = isset($input["avaliado"]) ? $input["avaliado"] : 0; // valor padrão

    $query = $db->prepare("
        UPDATE tb_salvo 
        SET notaRestaurante = ?, descricao = ?, avaliado = ?
        WHERE idSalvo = ?
    ");

    $query->execute([$nota, $descricao, $avaliado, $id]);

    echo json_encode(["status" => "atualizado"]);
    exit;
}

// ============================
// DELETE → DELETAR
// ============================
if ($method === "DELETE") {

    if (!$id) {
        echo json_encode(["erro" => "ID não informado"]);
        exit;
    }

    $query = $db->prepare("DELETE FROM tb_salvo WHERE idSalvo = ?");
    $query->execute([$id]);

    echo json_encode(["status" => "deletado"]);
    exit;
}

echo json_encode(["erro" => "Método não permitido"]);
