<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once("../models/conexao.php");

$metodo = $_SERVER["REQUEST_METHOD"];

switch ($metodo) {
    case "GET":
        $sql = "SELECT * FROM tb_usuario";
        $resultado = $conn->query($sql);
        $dados = [];

        while ($row = $resultado->fetch_assoc()) {
            $dados[] = $row;
        }

        echo json_encode($dados);
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $nome = $data["nomeUsuario"];
        $email = $data["emailUsuario"];
        $senha = $data["senhaUsuario"];

        $sql = "INSERT INTO tb_usuario (nomeUsuario, emailUsuario, senhaUsuario)
                VALUES ('$nome', '$email', '$senha')";

        if ($conn->query($sql)) {
            echo json_encode(["mensagem" => "Usuário criado com sucesso!"]);
        } else {
            echo json_encode(["erro" => $conn->error]);
        }
        break;

    default:
        echo json_encode(["erro" => "Método não suportado"]);
}
?>
