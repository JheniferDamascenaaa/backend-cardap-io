<?php



require_once __DIR__ . '../../controllers/usuario_controller.php';
require_once __DIR__ . '../../api/database.php';


$banco = new Banco();
$conexao = $banco->getConexao();

// Instancia o controller do usuário
$usuarioController = new UsuarioController($conexao);

$usuarioController->sair();