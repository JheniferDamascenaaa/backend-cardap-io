<?php 
/*class Restaurante{
    private $conexao;
    private $tabela = "tb_restaurante";

    public $idRestaurante;
    public $nomeRestaurante;
    public $endereco;
    public $idTags;
    public $notaMedia;
    public $visualizacao;
    public $descricao;

    public function __construct($Banco)
    {
        $this->conexao = $Banco;
    }

    public function buscar_pelo_id(){
        $query = "SELECT * FROM " . $this->tabela . " WHERE idRestaurante = ?";
        $conteudo = $this->conexao->prepare($query);
        $conteudo->bindParam(1, $this->idRestaurante);
        $conteudo->execute();
        return $conteudo;
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


  
  
?>