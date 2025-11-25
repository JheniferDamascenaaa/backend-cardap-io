<?php 
/*
class Restaurante {
    private $conexao;
    private $tabela = "tb_restaurante";

    public function __construct($Banco) {
        $this->conexao = $Banco;
    }

    public function buscar_por_id($id){
        $query = "SELECT * FROM " . $this->tabela . " WHERE idRestaurante = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listar() {
        $query = "SELECT * FROM " . $this->tabela;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionar($dados) {
        $query = "INSERT INTO " . $this->tabela . " (nomeRestaurante, endereco, idTags, notaMedia, descricao) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(1, $dados['nomeRestaurante']);
        $stmt->bindParam(2, $dados['endereco']);
        $stmt->bindParam(3, $dados['idTags']);
        $stmt->bindParam(4, $dados['notaMedia']);
        $stmt->bindParam(5, $dados['descricao']);
        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE " . $this->tabela . " SET nomeRestaurante=?, endereco=?, idTags=?, notaMedia=?, descricao=? WHERE idRestaurante=?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(1, $dados['nomeRestaurante']);
        $stmt->bindParam(2, $dados['endereco']);
        $stmt->bindParam(3, $dados['idTags']);
        $stmt->bindParam(4, $dados['notaMedia']);
        $stmt->bindParam(5, $dados['descricao']);
        $stmt->bindParam(6, $id);
        return $stmt->execute();
    }

    public function deletar($id) {
        $query = "DELETE FROM " . $this->tabela . " WHERE idRestaurante=?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt->execute();
    }
}
*/

class Restaurante {
    private $conexao;
    private $tabela = "tb_restaurante";

    public function __construct($Banco) {
        $this->conexao = $Banco;
    }

    public function buscar_por_id($id){
        $query = "SELECT * FROM " . $this->tabela . " WHERE idRestaurante = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listar() {
        $query = "SELECT * FROM " . $this->tabela;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionar($dados) {
        $query = "INSERT INTO " . $this->tabela . " 
            (nomeRestaurante, endereco, idTags, preco, caracteristicas, avaliacaoInicial, notaMedia, visualizacao, descricao, imagemPrincipal)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($query);

        $stmt->bindParam(1, $dados['nomeRestaurante']);
        $stmt->bindParam(2, $dados['endereco']);
        $stmt->bindParam(3, $dados['idTags']);
        $stmt->bindParam(4, $dados['preco']);
        $stmt->bindParam(5, $dados['caracteristicas']);
        $stmt->bindParam(6, $dados['avaliacaoInicial']);
        $stmt->bindParam(7, $dados['notaMedia']);
        $stmt->bindParam(8, $dados['visualizacao']);
        $stmt->bindParam(9, $dados['descricao']);
        $stmt->bindParam(10, $dados['imagemPrincipal']);

        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE " . $this->tabela . " SET 
            nomeRestaurante=?, endereco=?, idTags=?, preco=?, caracteristicas=?, avaliacaoInicial=?, notaMedia=?, visualizacao=?, descricao=?, imagemPrincipal=? 
            WHERE idRestaurante=?";

        $stmt = $this->conexao->prepare($query);

        $stmt->bindParam(1, $dados['nomeRestaurante']);
        $stmt->bindParam(2, $dados['endereco']);
        $stmt->bindParam(3, $dados['idTags']);
        $stmt->bindParam(4, $dados['preco']);
        $stmt->bindParam(5, $dados['caracteristicas']);
        $stmt->bindParam(6, $dados['avaliacaoInicial']);
        $stmt->bindParam(7, $dados['notaMedia']);
        $stmt->bindParam(8, $dados['visualizacao']);
        $stmt->bindParam(9, $dados['descricao']);
        $stmt->bindParam(10, $dados['imagemPrincipal']);
        $stmt->bindParam(11, $id);

        return $stmt->execute();
    }

    public function deletar($id) {
        $query = "DELETE FROM " . $this->tabela . " WHERE idRestaurante=?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt->execute();
    }
}

  
  
?>