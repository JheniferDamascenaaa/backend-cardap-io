<?php
session_start();

require_once __DIR__ . '../../controllers/usuario_controller.php';
require_once __DIR__ . '../../api/database.php';
require_once __DIR__ . '../../controllers/restaurante_controller.php';
require_once __DIR__ . '../../controllers/salvo_controller.php';


$banco = new Banco();
$conexao = $banco->getConexao();

$usuarioController = new UsuarioController($conexao);
$salvo = new Salvo($conexao);
$restauranteController = new RestauranteController($conexao);

// Verifica login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$idUsuario = $usuarioLogado['idUsuario'];
$mensagemSucesso = "";

// --- UPLOAD DE FOTO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $foto = $_FILES['foto'];

    if ($foto['error'] === 0) {
        $pasta = __DIR__ . "/uploads/fotos_perfil/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) $ext = 'jpg';

        $nomeArquivo = "perfil_{$idUsuario}_" . time() . ".{$ext}";
        $caminhoCompleto = $pasta . $nomeArquivo;

        if (move_uploaded_file($foto['tmp_name'], $caminhoCompleto)) {
            $dadosImagem = file_get_contents($caminhoCompleto);
            $base64 = 'data:image/' . $ext . ';base64,' . base64_encode($dadosImagem);

            $resultadoAtualizar = $usuarioController->atualizar($idUsuario, ['fotoPerfil' => $base64]);

            if ($resultadoAtualizar['success'] ?? false) {
                // Atualiza sessão e variável local
                $_SESSION['usuario']['fotoPerfil'] = $base64;
                $usuarioLogado['fotoPerfil'] = $base64;

                echo json_encode([
                    'success' => true,
                    'foto' => $base64
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Foto movida, mas não foi possível atualizar o banco'
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Falha ao mover arquivo']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Erro no upload']);
    }
    exit;
}

// --- ATUALIZA NOME ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_nome'])) {
    $novoNome = trim($_POST['novo_nome']);
    if ($novoNome && $novoNome !== $usuarioLogado['nomeUsuario']) {
        $resultado = $usuarioController->atualizar($idUsuario, [
            'nomeUsuario' => $novoNome,
            'email' => $usuarioLogado['email']
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

// --- CARREGA AVALIAÇÕES ---
$avaliacoes = $salvo->listar_por_usuario($idUsuario);

// --- CARREGA DADOS ATUALIZADOS DO USUÁRIO ---
$dadosAtualizados = $usuarioController->buscarPorId($idUsuario);
if (!isset($dadosAtualizados['error'])) {
    $usuarioLogado = array_merge($usuarioLogado, $dadosAtualizados);
    $_SESSION['usuario'] = $usuarioLogado;
}

// --- FUNÇÕES AUXILIARES ---
function tagNome($id) {
    $tags = [
        1 => "Chinesa", 2 => "Italiana", 3 => "Japonesa", 4 => "Brasileira",
        5 => "Mexicana", 6 => "Indiana", 7 => "Fast Food", 8 => "Vegana", 9 => "Argentina"
    ];
    return $tags[$id] ?? "Outro";
}

function gerarEstrelas($nota) {
    $cheias = round($nota);
    $html = "";
    for ($i = 1; $i <= 5; $i++) $html .= $i <= $cheias ? "★" : "☆";
    return $html;
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
        <a href="home.php" class="logo">
            <img src="imagens/logo-transparente-marrom.png" alt="Logo" />
        </a>
        <div class="icons">
            <a href="perfil.php" style="display: inline-block; margin-left: 15px;">
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

            <?php if (!empty($usuarioLogado['fotoPerfil'])): ?>
                <img id="imagem-perfil" 
                    src="<?= htmlspecialchars($usuarioLogado['fotoPerfil']) ?>" 
                    style="width:100%; height:100%; object-fit:cover; border-radius:50%;" />
            <?php else: ?>
                <img id="imagem-perfil" 
                        src="<?= htmlspecialchars($usuarioLogado['fotoPerfil'] ) ?>" 
                       >
                                    <span id="icone-foto">📷</span>
                                <?php endif; ?>

                        <input type="file" id="upload-foto" accept="image/*" style="display:none;" />




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

<div id="mensagem-foto" style="color:green; margin-bottom:10px;"></div>

    <?php foreach ($avaliacoes as $a): 
        $restaurante = $restauranteController->buscarPorId($a['idRestaurante']);
        if (isset($restaurante['error'])) continue;
    ?>
        <a href="restaurante.php?id=<?= $restaurante['idRestaurante'] ?>" class="card">
            <img src="<?= $restaurante['imagemPrincipal'] ?: 'imagens/placeholder.jpg' ?>" 
                 alt="Imagem do restaurante" 
                 class="card-imagem">

            <div class="card-info">
                <div class="card-topo">
                    <div>
                        <h3><?= htmlspecialchars($restaurante['nomeRestaurante']) ?></h3>
                        <strong><?= tagNome($restaurante['idTags']) ?></strong>
                    </div>
                </div>

                <div class="caracteristica">
                    <p><?= $a['preco'] ?: $restaurante['preco'] ?></p>

                    <?php 
                    $caracteristicas = explode(",", $a['caracteristicas'] ?: $restaurante['caracteristicas']);
                    foreach ($caracteristicas as $c): ?>
                        <p><?= trim($c) ?></p>
                    <?php endforeach; ?>
                </div>

                <div class="estrelas"><?= gerarEstrelas($a['notaRestaurante']) ?></div>
            </div>
        </a>
    <?php endforeach; ?>
         



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
const iconeFoto = document.getElementById("icone-foto"); // emoji 📷

// Crie uma div para mensagens, se não existir
let mensagemFoto = document.getElementById("mensagem-foto");
if (!mensagemFoto) {
    mensagemFoto = document.createElement("div");
    mensagemFoto.id = "mensagem-foto";
    mensagemFoto.style.color = "green";
    mensagemFoto.style.marginBottom = "10px";
    document.querySelector(".perfil-container").prepend(mensagemFoto);
}

btnFoto.addEventListener("click", () => inputFoto.click());

inputFoto.addEventListener("change", () => {
    const file = inputFoto.files[0];
    if (!file || !file.type.startsWith("image/")) return;

    // Desabilita botão e mostra carregando
    btnFoto.disabled = true;
    btnFoto.textContent = "Carregando...";
    mensagemFoto.textContent = ""; // limpa mensagens anteriores

    const formData = new FormData();
    formData.append("foto", file);

    fetch("perfil.php", {
        method: "POST",
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        // Habilita botão novamente
        btnFoto.disabled = false;
        btnFoto.textContent = "+";

        if (data.success) {
            // Atualiza a imagem de perfil
            imgPerfil.src = data.foto;
            imgPerfil.style.display = "block";
            imgPerfil.style.width = "100%";
            imgPerfil.style.height = "100%";
            imgPerfil.style.objectFit = "cover";
            imgPerfil.style.borderRadius = "50%";

            if (iconeFoto) iconeFoto.style.display = "none";

            // MOSTRA MENSAGEM DE SUCESSO
            mensagemFoto.style.color = "green";
            mensagemFoto.textContent = data.message || "Foto enviada com sucesso!";
            setTimeout(() => mensagemFoto.textContent = "", 3000); // some depois de 3s
        } else {
            // MOSTRA MENSAGEM DE ERRO
            mensagemFoto.style.color = "red";
            mensagemFoto.textContent = data.error || "Erro ao enviar a foto";
            setTimeout(() => mensagemFoto.textContent = "", 5000);
        }
    })
    .catch(err => {
        btnFoto.disabled = false;
        btnFoto.textContent = "+";

        mensagemFoto.style.color = "red";
        mensagemFoto.textContent = "Erro inesperado: " + err;
        setTimeout(() => mensagemFoto.textContent = "", 5000);
    });
});

</script>
</body>

</html>