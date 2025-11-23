<?php
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

    if (isset($_GET["usuario"])) {
        // Lista todos os salvos de um usuário
        $query = $db->prepare("SELECT * FROM tb_salvo WHERE idUsuario = ?");
        $query->execute([$_GET["usuario"]]);
        echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    if ($id) {
        // Busca por ID
        $query = $db->prepare("SELECT * FROM tb_salvo WHERE idSalvo = ?");
        $query->execute([$id]);
        echo json_encode($query->fetch(PDO::FETCH_ASSOC));
        exit;
    }

    echo json_encode(["erro" => "Use ?usuario=ID ou ?id=ID"]);
    exit;
}

// ============================
// POST → Adicionar
// ============================
if ($method === "POST") {
    $query = $db->prepare("
        INSERT INTO tb_salvo (idUsuario, idRestaurante, notaRestaurante, descricao, avaliado)
        VALUES (?, ?, ?, ?, ?)
    ");

    $query->execute([
        $input["idUsuario"] ?? 0,
        $input["idRestaurante"] ?? 0,
        $input["notaRestaurante"] ?? "",
        $input["descricao"] ?? "",
        $input["avaliado"] ?? 0
    ]);

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

    $nota = $input["notaRestaurante"] ?? null;
    $descricao = $input["descricao"] ?? "";
    $avaliado = $input["avaliado"] ?? 0;

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
// DELETE → Deletar
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
