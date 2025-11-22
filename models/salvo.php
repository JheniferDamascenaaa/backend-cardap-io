<?php 
class Salvos{
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

?>