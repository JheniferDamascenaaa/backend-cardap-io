<?php 
class Restaurante{
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
  
?>