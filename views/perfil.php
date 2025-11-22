<?php
session_start();

require_once __DIR__ . '../../controllers/usuario_controller.php';
require_once __DIR__ . '../../api/database.php';


$banco = new Banco();
$conexao = $banco->getConexao();
$usuarioController = new UsuarioController($conexao);

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$mensagemSucesso = "";

// Atualiza nome se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_nome'])) {
    $novoNome = trim($_POST['novo_nome']);
    if ($novoNome && $novoNome !== $usuarioLogado['nomeUsuario']) {
        $resultado = $usuarioController->atualizar($usuarioLogado['idUsuario'], [
            'nomeUsuario' => $novoNome,
            'email' => $usuarioLogado['email'] // email precisa ser enviado também
        ]);
        if ($resultado['success']) {
            $_SESSION['usuario']['nomeUsuario'] = $novoNome;
            $usuarioLogado['nomeUsuario'] = $novoNome;
            $mensagemSucesso = "Nome atualizado com sucesso!";
        } else {
            $mensagemSucesso = "Erro ao atualizar o nome.";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mídia</title>
 <!--   <link rel="stylesheet" href="perfil-style.css" />  -->
    <link rel="stylesheet" href="../views/estilos/perfil-style.css" />  

</head>

<body>
    <header>
        <a href="home.html" class="logo">
            <img src="imagens/logo-transparente-marrom.png" alt="Logo" />
        </a>
        <div class="icons">
            <a href="perfil.html" style="display: inline-block; margin-left: 15px;">
                <svg width="24" height="24" fill="#932C30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                </svg>
            </a>

            <a href="sair.php" class="btn-sair">Sair</a>
        </div>
    </header>

    <div class="perfil-container">
        <div class="perfil-topo">
            <button class="btn-voltar" onclick="history.back()">←</button>
            <div class="foto-perfil">
                <img id="imagem-perfil" src="" alt="Foto de Perfil" style="display:none;" />
                <input type="file" id="upload-foto" accept="image/*" style="display:none;" />
                <span>📷</span>
                <button class="btn-foto">+</button>
            </div>
            <div class="info-usuario">
                <div class="nome-editavel">
                    <h2 id="nome-display">
                        <?= htmlspecialchars($usuarioLogado['nomeUsuario']) ?>
                        <button class="btn-nome" id="editar-nome-btn">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 
                          7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 
                          1.003 0 0 0-1.42 0l-1.83 1.83 
                          3.75 3.75 1.84-1.82z" />
                            </svg>
                        </button>
                    </h2>    
                    
                    <input type="text" id="nome-input" class="input-nome" value="<?= htmlspecialchars($usuarioLogado['nomeUsuario']) ?>"  />
                </div>
                <hr style="background-color: #932C30;" />
                <p>Restaurantes Avaliados</p>
            </div>
        </div>

        <div class="cards-container">
            <a href="restaurante.html" class="card">

                <img src="imagens/spoleto___5kf_US12T4ZtglivHStU2.jpg" alt="" class="card-imagem">

                <div class="card-info">
                    <div class="card-topo">
                        <div>
                            <h3>Spoleto</h3>
                            <strong>Italiana</strong>
                        </div>
                    </div>

                    <div class="caracteristica">
                        <p>$$$</p>
                        <p>Família</p>
                        <p>Amigos</p>
                    </div>

                    <div class="estrelas">★★★☆☆</div>
                </div>
            </a>


            <a href="restaurante.html" class="card">

                <div class="card-imagem"></div>

                <div class="card-info">
                    <div class="card-topo">
                        <div>
                            <h3>China in Box</h3>
                            <strong>Chinesa</strong>
                        </div>
                    </div>

                    <div class="caracteristica">
                        <p>$$</p>
                        <p>Família</p>
                    </div>

                    <div class="estrelas">★★★☆☆</div>
                </div>
            </a>

        </div>

    </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const cards = document.querySelectorAll(".card");
            cards.forEach(card => {
                const botoes = card.querySelectorAll(".acoes button");
                botoes.forEach(botao => {
                    botao.addEventListener("click", () => {
                        botoes.forEach(b => b.classList.remove("ativo"));
                        botao.classList.add("ativo");
                    });
                });
            });

            const nomeDisplay = document.getElementById("nome-display");
            const nomeInput = document.getElementById("nome-input");
            const editarBtn = document.getElementById("editar-nome-btn");

            editarBtn.addEventListener("click", () => {
                nomeInput.value = nomeDisplay.textContent.trim();
                nomeDisplay.style.display = "none";
                nomeInput.style.display = "block";
                nomeInput.focus();
            });

            nomeInput.addEventListener("blur", salvarNome);
            nomeInput.addEventListener("keydown", (e) => {
                if (e.key === "Enter") salvarNome();
            });

            function salvarNome() {
                const novoNome = nomeInput.value.trim();
                if (novoNome) nomeDisplay.childNodes[0].textContent = novoNome + " ";
                nomeInput.style.display = "none";
                nomeDisplay.style.display = "block";
            }
        });
    </script>

    <script>
        const btnFoto = document.querySelector(".btn-foto");
        const inputFoto = document.getElementById("upload-foto");
        const imgPerfil = document.getElementById("imagem-perfil");
        const fotoPerfil = document.querySelector(".foto-perfil");

        btnFoto.addEventListener("click", () => {
            inputFoto.click();
        });

        inputFoto.addEventListener("change", () => {
            const file = inputFoto.files[0];
            if (file && file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imgPerfil.src = e.target.result;
                    imgPerfil.style.display = "block";
                    imgPerfil.style.width = "100%";
                    imgPerfil.style.height = "100%";
                    imgPerfil.style.objectFit = "cover";
                    imgPerfil.style.borderRadius = "50%";
                    // Remove emoji (📷) se estiver visível
                    const emoji = fotoPerfil.querySelector("span");
                    if (emoji) emoji.style.display = "none";
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>