<?php
header("Content-Type: application/json");
require_once __DIR__ . "/database.php";

$banco = new Banco();
$db = $banco->getConexao();


$method = $_SERVER["REQUEST_METHOD"];

// URL → pegar ID se existir
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", trim($path, "/"));
$id = end($parts);
$id = is_numeric($id) ? intval($id) : null;

// JSON recebido
$input = json_decode(file_get_contents("php://input"), true) ?? [];


if ($method === "GET") {

   
    if (isset($_GET["usuario"])) {
        $query = $db->prepare("SELECT * FROM tb_salvo WHERE idUsuario = ?");
        $query->execute([$_GET["usuario"]]);
        echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

 
    if ($id) {
        $query = $db->prepare("SELECT * FROM tb_salvo WHERE idSalvo = ?");
        $query->execute([$id]);
        echo json_encode($query->fetch(PDO::FETCH_ASSOC));
        exit;
    }


        $query = $db->query("
        SELECT s.*, u.nomeUsuario
        FROM tb_salvo s
        INNER JOIN tb_usuario u ON u.idUsuario = s.idUsuario
    ");
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    exit;
}



if ($method === "POST") {
    $query = $db->prepare("
        INSERT INTO tb_salvo 
        (idUsuario, idRestaurante, notaRestaurante, descricao, preco, caracteristicas)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $query->execute([
        $input["idUsuario"] ?? null,
        $input["idRestaurante"] ?? null,
        $input["notaRestaurante"] ?? 0,
        $input["descricao"] ?? "",
        $input["preco"] ?? "",
        $input["caracteristicas"] ?? ""
    ]);

    echo json_encode(["status" => "ok"]); // sem idNovo
    exit;
}



if ($method === "PUT") {

    if (!$id) {
        echo json_encode(["erro" => "ID não informado"]);
        exit;
    }

    // Pega os valores do JSON ou define padrão
    $idUsuario = $input["idUsuario"] ?? null;
    $idRestaurante = $input["idRestaurante"] ?? null;
    $nota = $input["notaRestaurante"] ?? null;
    $descricao = $input["descricao"] ?? "";
    $preco = $input["preco"] ?? "";
    $caracteristicas = $input["caracteristicas"] ?? "";

    $query = $db->prepare("
        UPDATE tb_salvo
        SET idUsuario = ?, idRestaurante = ?, notaRestaurante = ?, descricao = ?, preco = ?, caracteristicas = ?
        WHERE idSalvo = ?
    ");

    $query->execute([$idUsuario, $idRestaurante, $nota, $descricao, $preco, $caracteristicas, $id]);

    echo json_encode(["status" => "atualizado"]);
    exit;
}




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
