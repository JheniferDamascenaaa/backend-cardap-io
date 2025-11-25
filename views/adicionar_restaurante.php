<?php
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nomeRestaurante'] ?? null;
    $endereco = $_POST['endereco'] ?? null;
    $idTags = $_POST['idTags'] ?? null;
    $caracteristicas = $_POST['caracteristicas'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $avaliacaoInicial = floatval($_POST['avaliacaoInicial'] ?? 0);
    $notaMedia = $avaliacaoInicial; // agora notaMedia = avaliacaoInicial
    $visualizacao = intval($_POST['visualizacao'] ?? 0);
    $descricao = $_POST['descricao'] ?? '';
    $imagemPrincipal = $_POST['imagemPrincipal'] ?? null;

    if ($nome && $endereco && $idTags && $notaMedia !== null && $descricao) {
        $url = 'http://localhost/cardapio-back/api/restaurante_api.php';

        $data = json_encode([
            'nomeRestaurante' => $nome,
            'endereco' => $endereco,
            'idTags' => $idTags,
            'caracteristicas' => $caracteristicas,
            'preco' => $preco,
            'avaliacaoInicial' => $avaliacaoInicial,
            'notaMedia' => $notaMedia,
            'visualizacao' => $visualizacao,
            'descricao' => $descricao,
            'imagemPrincipal' => $imagemPrincipal
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
                $sucesso = "Restaurante cadastrado com sucesso!";
                $_POST = [];
            } else {

                $erro .= ' | Resposta API: ' . $response;
            }
        }

        curl_close($ch);
    } else {
        $erro = 'Preencha todos os campos obrigatórios: nome, endereço, culinária, nota média e descrição.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mídia — Cadastrar Restaurante</title>
  <link rel="stylesheet" href="./estilos/adicionar-restaurante.css" />
</head>
<body>
  <header>
    <a href="home.php" class="logo"><img src="imagens/logo-transparente-marrom.png" alt="Logo" /></a>
    <div class="icons">
      <!-- ícones aqui -->
    </div>
  </header>

  <main class="main">
    <section class="cadastro-card">
      <?php if ($erro): ?><div style="color:red; margin-bottom:10px;"><?php echo $erro; ?></div><?php endif; ?>
      <?php if ($sucesso): ?><div style="color:green; margin-bottom:10px;"><?php echo $sucesso; ?></div><?php endif; ?>

      <form id="form-cadastro" class="card-form" method="POST" action="">
        <div class="left-column">
          <label class="upload-area" id="upload-area">
            <input type="file" id="input-images" accept="image/*" style="display:none" />
            <div class="upload-placeholder"><div class="upload-text">Arraste ou clique para adicionar imagens</div></div>
          </label>
          <div id="thumbs" class="thumbs-container"></div>

          <div class="rating-row">
            <div class="stars" id="stars">
              <span class="star" data-value="1">☆</span>
              <span class="star" data-value="2">☆</span>
              <span class="star" data-value="3">☆</span>
              <span class="star" data-value="4">☆</span>
              <span class="star" data-value="5">☆</span>
            </div>
            <input type="hidden" name="avaliacaoInicial" id="rating-value" value="0" />
            <input type="hidden" name="notaMedia" value="0" />
          </div>
        </div>

        <div class="right-column">
          <label for="nomeRestaurante">Nome do Restaurante</label>
          <input id="nomeRestaurante" name="nomeRestaurante" placeholder="Qual o nome do restaurante?" required />

          <label for="endereco">Endereço</label>
          <input id="endereco" name="endereco" placeholder="Qual o endereço?" required />

          <label for="culinaria">Culinária</label>
          <select id="culinaria" name="idTags" required>
            <option value="">-- Selecione --</option>
            <option value="1">Chinesa</option>
            <option value="2">Italiana</option>
            <option value="3">Japonesa</option>
            <option value="4">Brasileira</option>
            <option value="5">Mexicana</option>
            <option value="6">Indiana</option>
            <option value="7">Fast Food</option>
            <option value="8">Vegana</option>
            <option value="9">Argentina</option>
          </select>

          <label for="descricao">Pontos de destaque</label>
          <textarea id="descricao" name="descricao" placeholder="Ex: Ambiente acolhedor" required></textarea>


        <div class="caracteristicas">
            <div class="chips">

                <!-- Campo oculto que VAI para o $_POST -->
                <input type="hidden" name="caracteristicas" id="caracteristicas">

                <button type="button" class="chip" data-value="Familia">Família</button>
                <button type="button" class="chip" data-value="Amigos">Amigos</button>
                <button type="button" class="chip" data-value="Encontros">Encontros</button>

            </div>
       </div>




          <div class="precos">
            <div class="price-options">
              <label class="price"><input type="radio" name="preco" value="$" />$</label>
              <label class="price"><input type="radio" name="preco" value="$$" />$$</label>
              <label class="price"><input type="radio" name="preco" value="$$$" />$$$</label>
            </div>
          </div>

          <div class="actions-row">
            <button type="submit" id="btn-cadastrar" class="btn-primary">Adicionar Restaurante</button>
          </div>
        </div>
        <input type="hidden" name="imagemPrincipal" id="imagemPrincipal">
      </form>
    </section>
  </main>

<script>
// ---------- imagens ----------
const inputImages = document.getElementById('input-images');
const thumbs = document.getElementById('thumbs');
const uploadArea = document.getElementById('upload-area');
const hiddenImagem = document.getElementById('imagemPrincipal');
let filesArray = [];

uploadArea.addEventListener('click', () => inputImages.click());
uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
uploadArea.addEventListener('dragleave', e => uploadArea.classList.remove('dragover'));
uploadArea.addEventListener('drop', e => {
  e.preventDefault();
  uploadArea.classList.remove('dragover');
  handleFiles(Array.from(e.dataTransfer.files));
});
inputImages.addEventListener('change', e => { handleFiles(Array.from(e.target.files)); inputImages.value = ''; });

function handleFiles(list) {
  const images = list.filter(f => f.type.startsWith('image/'));
  filesArray.push(...images);
  renderThumbs();
}

function renderThumbs() {
  thumbs.innerHTML = '';
  filesArray.forEach((file, idx) => {
    const url = URL.createObjectURL(file);
    const div = document.createElement('div');
    div.className = 'thumb';
    div.innerHTML = `<img src="${url}" alt="thumb"><button type="button" class="remove" data-idx="${idx}">✕</button>`;
    thumbs.appendChild(div);
  });
  thumbs.querySelectorAll('.remove').forEach(btn => {
    btn.addEventListener('click', e => { filesArray.splice(Number(e.target.dataset.idx), 1); renderThumbs(); });
  });
}

// ---------- estrelas ----------
const stars = document.querySelectorAll('#stars .star');
const ratingValue = document.getElementById('rating-value');
stars.forEach(s => {
  s.addEventListener('click', () => setRating(Number(s.dataset.value)));
  s.addEventListener('mouseover', () => highlightStars(Number(s.dataset.value)));
  s.addEventListener('mouseout', () => highlightStars(Number(ratingValue.value)));
});
function setRating(v) { ratingValue.value = v; highlightStars(v); }
function highlightStars(v) { stars.forEach(s => s.textContent = Number(s.dataset.value) <= v ? '★' : '☆'); }

// ---------- chips ----------
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

// ---------- submit ----------
const form = document.getElementById('form-cadastro');
form.addEventListener('submit', e => {
  if (filesArray[0]) {
    e.preventDefault();
    const file = filesArray[0];
    const reader = new FileReader();
    reader.onloadend = () => {
      hiddenImagem.value = reader.result; 
      form.submit();
    };
    reader.readAsDataURL(file);
  }
});
</script>
</body>
</html>
