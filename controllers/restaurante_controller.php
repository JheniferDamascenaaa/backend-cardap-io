<?php

require_once __DIR__ . './../models/restaurante.php';

class RestauranteController {

    private $restauranteModel;

    public function __construct($db) {
        $this->restauranteModel = new Restaurante($db);
    }

    public function listar() {
        return $this->restauranteModel->listar();
    }

    public function buscarPorId($id) {
        $resultado = $this->restauranteModel->buscar_por_id($id);
        return $resultado ?: ["error" => "Restaurante não encontrado"];
    }

    public function adicionar($dados) {
        if (!$dados || !isset($dados["nomeRestaurante"], $dados["endereco"], $dados["idTags"], $dados["notaMedia"], $dados["descricao"])) {
            return ["error" => "Dados incompletos"];
        }

        $sucesso = $this->restauranteModel->adicionar($dados);
        return $sucesso ? ["success" => true] : ["error" => "Erro ao adicionar restaurante"];
    }

    public function atualizar($id, $dados) {
        if (!$dados) {
            return ["error" => "Dados inválidos"];
        }
        $sucesso = $this->restauranteModel->atualizar($id, $dados);
        return $sucesso ? ["success" => true, "message" => "Restaurante atualizado"] : ["error" => "Erro ao atualizar restaurante"];
    }

    public function deletar($id) {
        $sucesso = $this->restauranteModel->deletar($id);
        return $sucesso ? ["success" => true, "message" => "Restaurante deletado"] : ["error" => "Erro ao deletar restaurante"];
    }
}
