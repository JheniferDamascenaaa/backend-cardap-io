<?php require_once 'config/database.php';

$banco = new Banco();
$conexao = $banco->getConexao();

if ($conexao){
    echo "Conexão realizada";
} else {
    echo " Falha ao conectar";
}