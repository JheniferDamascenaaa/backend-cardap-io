<?php 

    class Usuario {

    private $conexao;
    private $tabela = 'tb_usuario';

    public $idUsuario;
    public $nomeUsuario;
    public $email;
    public $senha;
    public $fotoPerfil; // <-- ADICIONADO

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

    // Adicionar usuário (não exige foto)
    public function adicionar($dados){
        $query = "INSERT INTO " . $this->tabela . " (nomeUsuario, email, senha, fotoPerfil)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($query);

        $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

        $foto = $dados['fotoPerfil'] ?? null;

        return $stmt->execute([
            $dados['nomeUsuario'],
            $dados['email'],
            $senhaHash,
            $foto
        ]);
    }

    // Atualizar usuário (com ou sem senha, com ou sem foto)
    public function atualizar($id, $dados){

        $campos = [];
        $params = [];

        // Campos opcionais
        if (isset($dados['nomeUsuario'])) {
            $campos[] = "nomeUsuario = ?";
            $params[] = $dados['nomeUsuario'];
        }

        if (isset($dados['email'])) {
            $campos[] = "email = ?";
            $params[] = $dados['email'];
        }

        if (!empty($dados['senha'])) {
            $campos[] = "senha = ?";
            $params[] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        }

        if (array_key_exists('fotoPerfil', $dados)) { // permite NULL
            $campos[] = "fotoPerfil = ?";
            $params[] = $dados['fotoPerfil'];
        }

        if (empty($campos)) return false;

        $query = "UPDATE " . $this->tabela . " SET " . implode(", ", $campos) . " WHERE idUsuario = ?";
        $params[] = $id;

        $stmt = $this->conexao->prepare($query);
        return $stmt->execute($params);
    }

    public function deletar($id){
        $query = "DELETE FROM " . $this->tabela . " WHERE idUsuario = ?";
        $stmt = $this->conexao->prepare($query);
        return $stmt->execute([$id]);
    }

    public function listar(){
        $query = "SELECT idUsuario, nomeUsuario, email, fotoPerfil FROM " . $this->tabela;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar_por_id($id){
        $query = "SELECT idUsuario, nomeUsuario, email, fotoPerfil FROM " . $this->tabela . " WHERE idUsuario = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
