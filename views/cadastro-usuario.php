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
                $sucesso = "Cadastro realizado com sucesso! Você será redirecionado para a pagina de login";
                header("refresh:3;url=login.php");
                
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


<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Cadastro de Usuário</title>

<style>
  body { font-family: Arial, sans-serif; background:#FFF7F2; margin:0; }
  header { text-align:center; padding:40px 0; font-size:42px; font-weight:700; color:#3A2D2D; }
  .container { width:50%; margin:0 auto; padding:40px; }
  h2 { text-align:center; font-size:32px; color:#3A2D2D; }
  label { display:block; margin-top:25px; font-size:20px; color:#3A2D2D; }
  input { width:100%; height:45px; border-radius:10px; border:1px solid #ccc; padding:10px; font-size:18px; }
  .btn { margin-top:40px; width:100%; background:#A22525; color:white; padding:18px; border:none; border-radius:20px; font-size:22px; cursor:pointer; }
  .login { margin-top:20px; text-align:center; font-size:18px; }
  .login a { color:#000; font-weight:bold; }
</style>

</head>
<body>
<header>Cardap.io</header>
<div class="container">

  <form method="POST" action="">
    <h2>Cadastro</h2>

    <label>Nome</label>
    <input type="text" name="nome" required />

    <label>E-mail</label>
    <input type="text" name="email" required />

    <label>Senha</label>
    <input type="password" name="senha" required />

    <!-- <label>Confirme sua senha</label>
    <input type="confirma_senha" name="confirma_senha" required />-->

    <button type="submit" class="btn">Cadastrar</button>

    <div class="login">Já tem conta? <a href="login.php">Entrar</a></div>
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
</body>
</html>