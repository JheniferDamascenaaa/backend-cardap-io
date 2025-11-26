<?php

require_once '../models/usuario.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct($db) {
        $this->usuarioModel = new Usuario($db);
    }

    public function listar() {
        return $this->usuarioModel->listar();
    }

    public function buscarPorId($id) {
        $usuario = $this->usuarioModel->buscar_por_id($id);
        return $usuario ?: ['error' => 'Usuário não encontrado'];
    }



    public function adicionar($dados) {
    if (!isset($dados['nomeUsuario'], $dados['email'], $dados['senha'])) {
        return ['error' => 'Todos os campos são obrigatórios'];
    }

    // fotoPerfil pode ser NULL ou string
    $dados['fotoPerfil'] = $dados['fotoPerfil'] ?? null;

    $sucesso = $this->usuarioModel->adicionar($dados);

    return $sucesso
        ? ['success' => true, 'message' => 'Usuário adicionado com sucesso']
        : ['success' => false, 'message' => 'Erro ao adicionar usuário'];
}

public function atualizar($id, $dados) {
    // fotoPerfil pode vir NULL, ou string, ou nem vir
    if (!array_key_exists('fotoPerfil', $dados)) {
        // não mexe na foto
    }

    $sucesso = $this->usuarioModel->atualizar($id, $dados);

    return $sucesso
        ? ['success' => true, 'message' => 'Usuário atualizado com sucesso']
        : ['success' => false, 'message' => 'Erro ao atualizar usuário'];
}



    public function deletar($id) {
        $sucesso = $this->usuarioModel->deletar($id);
        return $sucesso
            ? ['success' => true, 'message' => 'Usuário deletado com sucesso']
            : ['success' => false, 'message' => 'Erro ao deletar usuário'];
    }

    // LOGIN CORRIGIDO
    public function login($email, $senha) {
        // Define o email no model
        $this->usuarioModel->email = $email;

        // Busca usuário pelo email
        $usuario = $this->usuarioModel->buscar_por_email();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return [
                'success' => true,
                'usuario' => [
                    'idUsuario' => $usuario['idUsuario'],
                    'nomeUsuario' => $usuario['nomeUsuario'],
                    'email' => $usuario['email']
                ]
            ];
        }

        return ['success' => false, 'message' => 'Email ou senha incorretos'];
    }

    public function sair(){
        session_start();
        // Limpa todas as variáveis de sessão
        $_SESSION = [];
        // Destroi a sessão
        session_destroy();
        // Redireciona para a página de login
        header("Location: ../views/login.php");
        exit;

    }
}
?>