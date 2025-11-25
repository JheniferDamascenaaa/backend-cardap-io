<?php
session_start(); 


if (isset($_SESSION['msg'])) {
    echo '<div class="alert">' . $_SESSION['msg'] . '</div>';
    unset($_SESSION['msg']);
}


if (!isset($_SESSION['usuario'])) {
    $_SESSION['msg'] = "Você precisa estar logado para avaliar um restaurante.";
    header("Location: login.php");
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$idUsuario = $usuarioLogado['idUsuario']; // ID do usuário logado


$idRestaurante = $_GET['id'] ?? null;
$nomeRestaurante = $_GET['nome'] ?? 'Restaurante não selecionado';
$imagemRestaurante = 'imagens/placeholder.jpg'; // imagem padrão

if ($idRestaurante) {
    $apiUrl = "http://localhost/cardapio-back/api/restaurante_api.php";
    $todosRestaurantes = json_decode(file_get_contents($apiUrl), true);
    foreach ($todosRestaurantes as $r) {
        if ($r['idRestaurante'] == $idRestaurante) {
            $imagemRestaurante = $r['imagemPrincipal'] ?? $imagemRestaurante;
            break;
        }
    }
}


require_once __DIR__ . '/../controllers/salvo_controller.php';
require_once __DIR__ . '/../api/database.php';

$banco = new Banco();
$pdo = $banco->getConexao();
$controller = new Salvo($pdo);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ID do usuário logado (corrigido)
    $idUsuario = $usuarioLogado['idUsuario'];

    // Dados do formulário
    $idRestaurante = $_POST['idRestaurante'];
    $notaRestaurante = $_POST['notaRestaurante'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $caracteristicas = $_POST['caracteristicas'];

    $dados = [
        'idUsuario' => $idUsuario,
        'idRestaurante' => $idRestaurante,
        'notaRestaurante' => $notaRestaurante,
        'descricao' => $descricao,
        'preco' => $preco,
        'caracteristicas' => $caracteristicas
    ];

    $result = $controller->adicionar($dados);

    if ($result) {
        $_SESSION['msg'] = "Avaliação adicionada com sucesso!";
    } else {
        $_SESSION['msg'] = "Erro ao adicionar avaliação.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mídia — Cadastrar</title>
  <link rel="stylesheet" href="./estilos/adicionar-restaurante.css" />
</head>

<body>
  <header>
    <a href="home.php" class="logo">
      <img src="imagens/logo-transparente-marrom.png" alt="Logo" />
    </a>

    <div class="icons">
      <svg width="24" height="24" fill="#932c30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
        style="margin-right: 15px;">
        <path d="M10 2a8 8 0 015.29 13.71l5 5a1 1 0 01-1.42 1.42l-5-5A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z" />
      </svg>
      <a href="perfil.php" style="display: inline-block; margin-left: 15px;">
        <svg width="24" height="24" fill="#932c30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
        </svg>
      </a>
    </div>
  </header>


  <main class="main">
    <section class="cadastro-card">
      <form id="form-cadastro" class="card-form" method="POST">
        <div class="left-column">
          <!-- Área de upload / poster -->
          <label class="upload-area" id="upload-area" for="input-images">
            <input type="file" id="input-images" accept="image/*" multiple style="display:none" />
            <div class="upload-placeholder">
              <?php if ($imagemRestaurante): ?>
                <img src="<?= htmlspecialchars($imagemRestaurante) ?>" alt="Imagem do restaurante" class="preview-image" />
              <?php else: ?>
                <div class="upload-text">Arraste ou clique para adicionar imagens</div>
              <?php endif; ?>
            </div>
          </label>

          <!-- pré-visualizações -->
          <div class="thumbs" id="thumbs"></div>

          <!-- avaliação 5 estrelas -->
          <div class="rating-row">
            <div class="stars" id="stars">
              <span class="star" data-value="1">☆</span>
              <span class="star" data-value="2">☆</span>
              <span class="star" data-value="3">☆</span>
              <span class="star" data-value="4">☆</span>
              <span class="star" data-value="5">☆</span>
            </div>
            <input type="hidden" name="notaRestaurante" id="rating-value" value="0" />
          </div>
        </div>

        <div class="right-column">
          <label for="culinaria">Avaliar Restaurante: <?= htmlspecialchars($nomeRestaurante) ?></label> 

          <!-- características -->
          <div class="caracteristicas">
            <div class="chips">
              <button type="button" class="chip" data-value="Familia">Família</button>
              <button type="button" class="chip" data-value="Amigos">Amigos</button>
              <button type="button" class="chip" data-value="Encontros">Encontros</button>
            </div>
          </div>

          <!-- preço -->
          <div class="precos">
            <div class="price-options">
              <label class="price"><input type="radio" name="preco" value="$" />$</label>
              <label class="price"><input type="radio" name="preco" value="$$" />$$</label>
              <label class="price"><input type="radio" name="preco" value="$$$" />$$$</label>

            </div>
          </div>

          <!-- textarea -->
          <label for="comentario">Comentário</label>
          <textarea id="comentario" name="descricao" placeholder="Escreva seu comentário..."></textarea>

          <input type="hidden" name="idRestaurante" value="<?= htmlspecialchars($idRestaurante) ?>">
          <!-- <input type="hidden" name="idUsuario" value="5"> --> <!-- Exemplo, substitua pelo ID do usuário logado -->
           <input type="hidden" name="idUsuario" value="<?= $idUsuario ?>">
          <input type="hidden" name="caracteristicas" id="caracteristicas">

          <!-- botão cadastrar -->
          <div class="actions-row">
            <button type="submit" id="btn-cadastrar" class="btn-primary">Avaliar</button>
          </div>
        </div>
      </form>
    </section>
  </main>
  <!-- SCRIPTS: imagens, estrelas, chips, pesquisa de culinária, submit -->
  <script>
    // ---------- imagens múltiplas com preview e remoção ----------
    const inputImages = document.getElementById('input-images');
    const thumbs = document.getElementById('thumbs');
    const uploadArea = document.getElementById('upload-area');

    let filesArray = [];

    uploadArea.addEventListener('click', (e) => {
      // abrir o input file
      inputImages.click();
    });

    // arrastar e soltar
    uploadArea.addEventListener('dragover', e => {
      e.preventDefault();
      uploadArea.classList.add('dragover');
    });
    uploadArea.addEventListener('dragleave', e => {
      uploadArea.classList.remove('dragover');
    });
    uploadArea.addEventListener('drop', e => {
      e.preventDefault();
      uploadArea.classList.remove('dragover');
      const dropped = Array.from(e.dataTransfer.files);
      handleFiles(dropped);
    });

    inputImages.addEventListener('change', (e) => {
      handleFiles(Array.from(e.target.files));
      inputImages.value = '';
    });

    function handleFiles(list) {
      // só aceita imagens
      const images = list.filter(f => f.type && f.type.startsWith('image/'));
      images.forEach(file => {
        filesArray.push(file);
      });
      renderThumbs();
    }

    function renderThumbs() {
      thumbs.innerHTML = '';
      filesArray.forEach((file, idx) => {
        const url = URL.createObjectURL(file);
        const div = document.createElement('div');
        div.className = 'thumb';
        div.innerHTML = `
          <img src="${url}" alt="thumb">
          <button type="button" class="remove" data-idx="${idx}">✕</button>
        `;
        thumbs.appendChild(div);
      });

      // remover handler
      thumbs.querySelectorAll('.remove').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const i = Number(e.target.dataset.idx);
          filesArray.splice(i, 1);
          renderThumbs();
        });
      });
    }

    // ---------- estrelas (5) ----------
    const stars = document.querySelectorAll('#stars .star');
    const ratingValue = document.getElementById('rating-value');

    stars.forEach(s => {
      s.addEventListener('click', () => {
        const v = Number(s.dataset.value);
        setRating(v);
      });
      s.addEventListener('mouseover', () => {
        highlightStars(Number(s.dataset.value));
      });
      s.addEventListener('mouseout', () => {
        highlightStars(Number(ratingValue.value));
      });
    });

    function setRating(v) {
      ratingValue.value = v;
      highlightStars(v);
    }

    function highlightStars(v) {
      stars.forEach(s => {
        s.textContent = Number(s.dataset.value) <= v ? '★' : '☆';
      });
    }

    // ---------- chips (toggle) ----------
const chips = document.querySelectorAll(".chip");
const hiddenCaracteristicas = document.getElementById("caracteristicas");

chips.forEach(chip => {
    chip.addEventListener("click", () => {
        chip.classList.toggle("active");

        const selecionados = [...document.querySelectorAll(".chip.active")]
            .map(c => c.dataset.value);

        hiddenCaracteristicas.value = selecionados.join(", ");
    });
});
    // ---------- preço (estilização já em CSS) ----------
    // nada extra necessário aqui

    // ---------- pesquisa para filtrar o select de culinária ----------
    const searchCuisine = document.getElementById('search-cuisine');
    const selectCuisine = document.getElementById('culinaria');

    searchCuisine.addEventListener('input', () => {
      const q = searchCuisine.value.trim().toLowerCase();
      [...selectCuisine.options].forEach(opt => {
        if (opt.value === '') {
          opt.style.display = '';
          return;
        }
        opt.style.display = opt.text.toLowerCase().includes(q) ? '' : 'none';
      });
    });

    // ---------- submit (aqui somente logs; adapte para envio real) ----------
const btnCadastrar = document.getElementById('btn-cadastrar');

btnCadastrar.addEventListener('click', (e) => {
    e.preventDefault(); // Previnir envio padrão

    const data = {
        idRestaurante: document.querySelector('input[name="idRestaurante"]').value,
        idUsuario: document.querySelector('input[name="idUsuario"]').value, // já vem da sessão
        notaRestaurante: document.getElementById('rating-value').value,
        descricao: document.getElementById('comentario').value,
        preco: document.querySelector('input[name="preco"]:checked')?.value || '',
        caracteristicas: [...document.querySelectorAll('.chip.active')].map(c => c.dataset.value).join(', ')
    };

    if (!data.idUsuario) {
        alert("Você precisa estar logado para avaliar!");
        return;
    }

    fetch('/cardapio-back/api/salvo_api.php', { // seu endpoint real
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'ok') {
            alert('Avaliação enviada com sucesso!');
            location.reload(); // recarrega para mostrar avaliação ou limpar formulário
        } else {
            alert('Erro ao enviar avaliação.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Erro de conexão.');
    });
});</script>
</body>

</html>