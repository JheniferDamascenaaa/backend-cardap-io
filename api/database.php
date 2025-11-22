<?php 
class Banco{
    private $host = "127.0.0.1";
    private $nome_banco = "bd_cdpio";
    private $username = "root";
    private $senha_banco = "";
    public $conexao;
    

    public function getConexao(){
       $this->conexao = null;
        try {
            $this->conexao = new PDO(
                "mysql:host=".$this->host.";dbname=".$this->nome_banco,
                $this->username,
                $this->senha_banco
            );
            $this->conexao->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }
        return $this->conexao;
    }

}

