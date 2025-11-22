<?php 
class Tags{
    private $conexao;
    private $tabela = "tb_tags";

    public $idTags;
    public $nomeTag;

    public function __construct($Banco) {
        $this->conexao = $Banco;
    }

    public function listar(){
        $query = "SELECT * FROM " . $this->tabela;
        $conteudo = $this->conexao->prepare($query);
        $conteudo->execute();
        return $conteudo;

    }

  }

?>