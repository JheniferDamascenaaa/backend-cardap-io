<?php 
/*class Salvos{
    private $conexao;
    private $tabela = "tb_salvo";

    public $idSalvo;
    public $idUsuario;
    public $idRestaurante;
    public $notaRestaurante;
    public $avaliado;
    public $descricao; 

    public function __construct($Banco) {
        $this->conexao = $Banco;
    }
    
    public function listarPorUsuario(){
        $query  = "SELECT * FROM " . $this->tabela . " WHERE idUsuario = ?";
        $conteudo= $this->conexao->prepare($query);
        $conteudo ->bindParam(1, $this->idUsuario);
        $conteudo ->execute();
        return $conteudo;
    }

  }

?> */

class Salvos {
    private $conexao;
    private $tabela = "tb_salvo";

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

    // Listar todos os registros de um usuário
    public function listarPorUsuario() {
        $query = "SELECT * FROM " . $this->tabela . " WHERE idUsuario = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(1, $this->idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // retorna array associativo
    }

    // Inserir uma nova avaliação
    public function inserir() {
        $query = "INSERT INTO " . $this->tabela . " 
                  (idUsuario, idRestaurante, notaRestaurante, descricao, preco, caracteristicas)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($query);
        return $stmt->execute([
            $this->idUsuario,
            $this->idRestaurante,
            $this->notaRestaurante,
            $this->descricao,
            $this->preco,
            $this->caracteristicas
        ]);
    }


}
?>