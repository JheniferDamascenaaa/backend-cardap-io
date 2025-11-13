<?php
// Mostra erros do PHP (ajuda durante o desenvolvimento)
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "bd_cdpio"; // nome do banco que você importou no phpMyAdmin

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("❌ Erro na conexão: " . $conn->connect_error);
}

echo "✅ Conexão bem-sucedida com o banco de dados!<br>";

// Teste simples: mostra a data e hora do servidor MySQL
$result = $conn->query("SELECT NOW() AS data_atual");
if ($result && $row = $result->fetch_assoc()) {
    echo "🕒 Data/hora atual no MySQL: " . $row['data_atual'];
} else {
    echo "⚠️ Falha ao executar teste de consulta.";
}

?>
