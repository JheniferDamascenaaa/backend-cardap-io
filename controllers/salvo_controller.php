<?php

class Salvo {

    private $conexao;
    private $tabela = 'tb_salvo';

    // Atributos correspondentes à tabela
    public $idSalvo;
    public $idUsuario;
    public $idRestaurante;
    public $notaRestaurante;
    public $descricao;
    public $preco;
    public $caracteristicas;

    public function __construct($Banco) {
        $this->conexao = $Banco;
    }

    // Adiciona uma avaliação
   public function adicionar($dados) {
        $query = "INSERT INTO " . $this->tabela . " 
                  (idUsuario, idRestaurante, notaRestaurante, descricao, preco, caracteristicas) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($query);

        return $stmt->execute([
            $dados['idUsuario'],
            $dados['idRestaurante'],
            $dados['notaRestaurante'],
            $dados['descricao'],
            $dados['preco'],
            $dados['caracteristicas']
        ]);
    } 
    // Atualiza uma avaliação
    public function atualizar($id, $dados) {
        $query = "UPDATE " . $this->tabela . " 
                  SET idUsuario = ?, idRestaurante = ?, notaRestaurante = ?, descricao = ?, preco = ?, caracteristicas = ? 
                  WHERE idSalvo = ?";
        $stmt = $this->conexao->prepare($query);

        return $stmt->execute([
            $dados['idUsuario'],
            $dados['idRestaurante'],
            $dados['notaRestaurante'],
            $dados['descricao'],
            $dados['preco'],
            $dados['caracteristicas'],
            $id
        ]);
    }

    // Deleta uma avaliação
    public function deletar($id) {
        $query = "DELETE FROM " . $this->tabela . " WHERE idSalvo = ?";
        $stmt = $this->conexao->prepare($query);
        return $stmt->execute([$id]);
    }

    // Lista todas as avaliações
    public function listar() {
        $query = "SELECT * FROM " . $this->tabela;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca uma avaliação pelo ID
    public function buscar_por_id($id) {
        $query = "SELECT * FROM " . $this->tabela . " WHERE idSalvo = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lista avaliações de um usuário específico
    public function listar_por_usuario($idUsuario) {
        $query = "SELECT * FROM " . $this->tabela . " WHERE idUsuario = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>