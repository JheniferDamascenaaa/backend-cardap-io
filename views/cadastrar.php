<?php
session_start();
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;
   /* $confirma_senha =$_POST['confirma']; */



    if ($email && $senha) {

        $url = 'http://localhost/cardapio-back/api/usuario_api.php';

        // Dados para envio via POST como JSON
        $data = json_encode([
            'nomeUsuario'  => $nome,
            'email' => $email,
            'senha' => $senha,
            /*'confirma_senha' =>  $confirma_senha */

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
                $sucesso = "Cadastro realizado com sucesso! Faça login";
                header("refresh:3");
                
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
<!--    <link rel="stylesheet" href="login.css">  -->
    <link rel="stylesheet" href="../views/estilos/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="imagens/logo-transparente.png" alt="Logo" class="logo">
            <h2>Cadastre-se</h2>
            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" name="nome" id="username" placeholder="NOME" required>
                </div>

                <div class="input-group">
                    <input type="email" name="email" id="e-mail" placeholder="E-MAIL" required>
                </div>

                <div class="input-group">
                    <input type="password" name="senha" id="password" placeholder="SENHA" required>
                </div>

            <!--<div class="input-group">
                    <input type="password" name="password" id="password" placeholder="CONFIRMAR SENHA" required>
                </div>  -->
                
                <button type="submit" class="login-btn">CADASTRAR</button>
                </div>
                <br> 
                    <p class="forgot-create" style="display: flex;">Já tem conta?<a href="login.php" class="forgot-create" style="margin-left: 5px;">ENTRAR</a></p>
            </form>

                       <?php if (!empty($erro)): ?>
            <div style="
                background:#ffdddd;
                color:#a20000;
                padding:15px;
                margin-top:20px;
                border-radius:10px;
                font-size:18px;
                text-align:center;">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div style="
                background:#ddffdd;
                color:#006600;
                padding:15px;
                margin-top:20px;
                border-radius:10px;
                font-size:18px;
                text-align:center;">
                <?= $sucesso ?>
            </div>
        <?php endif; ?>

        </div>
    </div>
</body>
</html>