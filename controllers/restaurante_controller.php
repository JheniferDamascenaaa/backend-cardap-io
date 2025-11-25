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
        // Verifica campos obrigatórios
        if (
            !$dados || 
            !isset($dados["nomeRestaurante"], $dados["endereco"], $dados["idTags"], $dados["notaMedia"], $dados["descricao"])
        ) {
            return ["error" => "Dados incompletos"];
        }

        // Aplica valores default para campos opcionais
        $dadosPadronizados = array_merge([
            'preco' => '',
            'caracteristicas' => '',
            'avaliacaoInicial' => 0,
            'visualizacao' => 0,
            'imagemPrincipal' => null
        ], $dados);

        $sucesso = $this->restauranteModel->adicionar($dadosPadronizados);
        return $sucesso ? ["success" => true] : ["error" => "Erro ao adicionar restaurante"];
    }

    public function atualizar($id, $dados) {
        if (!$dados) {
            return ["error" => "Dados inválidos"];
        }

        // Aplica valores default se quiser manter consistência
        $dadosPadronizados = array_merge([
            'preco' => '',
            'caracteristicas' => '',
            'avaliacaoInicial' => 0,
            'visualizacao' => 0,
            'imagemPrincipal' => null
        ], $dados);

        $sucesso = $this->restauranteModel->atualizar($id, $dadosPadronizados);
        return $sucesso ? ["success" => true, "message" => "Restaurante atualizado"] : ["error" => "Erro ao atualizar restaurante"];
    }

    public function deletar($id) {
        $sucesso = $this->restauranteModel->deletar($id);
        return $sucesso ? ["success" => true, "message" => "Restaurante deletado"] : ["error" => "Erro ao deletar restaurante"];
    }
}