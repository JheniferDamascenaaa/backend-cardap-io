<?php

session_start();
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;

    if ($email && $senha) {

        $url = 'http://localhost/cardapio-back/api/usuario_api.php';

        // Dados para envio via POST como JSON
        $data = json_encode([
            'email' => $email,
            'senha' => $senha
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $erro = 'Erro de conexão: ' . curl_error($ch);
        } else {
            $resultado = json_decode($response, true);
            if (!empty($resultado['success'])) {
                $_SESSION['usuario'] = $resultado['usuario'];
                header('Location: home.html');
                exit;
            } else {
                $erro = $resultado['message'] ?? 'Email ou senha incorretos';
            }
        }

        curl_close($ch);

    } else {
        $erro = 'Preencha email e senha.';
    }
}
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hitOUflop - Login</title>
    <link rel="stylesheet" href="../views/estilos/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="imagens/logo-transparente.png" alt="Logo" class="logo">
            <h2>Seja bem-vindo</h2>
            <p>FAÇA LOGIN ABAIXO PARA ACESSAR</p>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <input type="text" name="email" id="e-mail" placeholder="DIGITE O SEU E-MAIL" required>
                </div>
                <div class="input-group">
                    <input type="password" name="senha" id="password" placeholder="DIGITE A SUA SENHA" required>
                </div>
                <button type="submit" class="login-btn">LOGIN</button>
                </div>
                <br> 
                <div class="infos" style="display: flex; justify-content: space-between;">
                    <a href="esqueceu.html" class="forgot-create">ESQUECI MINHA SENHA</a>
                    <a href="cadastrar.php" class="forgot-create">CADASTRE-SE</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>