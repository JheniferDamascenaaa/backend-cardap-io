<?php 

class Usuario {

    private $conexao;
    private $tabela = 'tb_usuario';

    public $idUsuario;
    public $nomeUsuario;
    public $email;
    public $senha;

    public function __construct($Banco){
        $this->conexao = $Banco;
    }

    // Busca usuário pelo email
    public function buscar_por_email(){
        $query = "SELECT * FROM " . $this->tabela . " WHERE email = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }  



    // Adiciona usuário (com hash da senha)
    public function adicionar($dados){
        $query = "INSERT INTO " . $this->tabela . " (nomeUsuario, email, senha) VALUES (?, ?, ?)";
        $stmt = $this->conexao->prepare($query);

        $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

        return $stmt->execute([$dados['nomeUsuario'], $dados['email'], $senhaHash]);
    }

    // Atualiza usuário
    public function atualizar($id, $dados){
        if (!empty($dados['senha'])) {
            $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $query = "UPDATE " . $this->tabela . " SET nomeUsuario = ?, email = ?, senha = ? WHERE idUsuario = ?";
            $stmt = $this->conexao->prepare($query);
            return $stmt->execute([$dados['nomeUsuario'], $dados['email'], $senhaHash, $id]);
        } else {
            $query = "UPDATE " . $this->tabela . " SET nomeUsuario = ?, email = ? WHERE idUsuario = ?";
            $stmt = $this->conexao->prepare($query);
            return $stmt->execute([$dados['nomeUsuario'], $dados['email'], $id]);
        }
    }

    // Deleta usuário  
    public function deletar($id){
        $query = "DELETE FROM " . $this->tabela . " WHERE idUsuario = ?";
        $stmt = $this->conexao->prepare($query);
        return $stmt->execute([$id]);
    }

    // Verifica senha com hash
    public function verificar_senha($senhaFornecida, $senhaBanco){
        return password_verify($senhaFornecida, $senhaBanco);
    }

    // Lista todos
    public function listar(){
        $query = "SELECT idUsuario, nomeUsuario, email FROM " . $this->tabela;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar por ID
    public function buscar_por_id($id){
        $query = "SELECT idUsuario, nomeUsuario, email FROM " . $this->tabela . " WHERE idUsuario = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
