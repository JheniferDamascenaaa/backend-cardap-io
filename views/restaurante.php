<?php


$apiUrl = "http://localhost/cardapio-back/api/restaurante_api.php";

// Pega o ID do restaurante enviado pela URL
$idRestaurante = $_GET['id'] ?? null;

$restaurante = null;
$comentarios = []; // Inicializa array de comentários

if ($idRestaurante) {
    // ---------------------------
    // BUSCA DADOS DO RESTAURANTE
    // ---------------------------
    $todosRestaurantes = json_decode(file_get_contents($apiUrl), true);
    if (is_array($todosRestaurantes)) {
        foreach ($todosRestaurantes as $r) {
            if ($r['idRestaurante'] == $idRestaurante) {
                $restaurante = $r;
                break;
            }
        }
    }

    // ---------------------------
    // BUSCA COMENTÁRIOS DO RESTAURANTE
    // ---------------------------
    $salvoApiUrl = "http://localhost/cardapio-back/api/salvo_api.php";
    $todosComentarios = json_decode(file_get_contents($salvoApiUrl), true);
    if (is_array($todosComentarios)) {
        foreach ($todosComentarios as $c) {
            if ($c['idRestaurante'] == $idRestaurante) {
                $comentarios[] = $c;
            }
        }
    }
}

// Função para traduzir idTags em nomes
function tagNome($id) {
    $tags = [
        1 => "Chinesa",
        2 => "Italiana",
        3 => "Japonesa",
        4 => "Brasileira",
        5 => "Mexicana",
        6 => "Indiana",
        7 => "Fast Food",
        8 => "Vegana",
        9 => "Argentina",
    ];
    return $tags[$id] ?? "Outro";
}

// Função para gerar estrelas
function gerarEstrelas($nota) {
    $cheias = round($nota);
    $html = "";
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= $cheias ? "★" : "☆";
    }
    return $html;
}

// Evita erro se restaurante não encontrado
if (!$restaurante) {
    die("Restaurante não encontrado.");
}
?>




<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurante</title>
  <link rel="stylesheet" href="./estilos//restaurante-style.css" />
</head>

<body>
  
  <header>
    <a href="home.php" class="logo">
      <img src="imagens/logo-transparente-marrom.png" alt="Logo" />
    </a>

    <div class="icons">
      <a href="perfil.php" style="display: inline-block; margin-left: 15px;">
        <svg width="24" height="24" fill="#932c30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
        </svg>
      </a>
    </div>
  </header>

<main>
  <div class="container">
    <!-- IMAGEM -->
    <div class="imagem-box">
      <img class="foto" src="<?= htmlspecialchars($restaurante['imagemPrincipal'] ?? 'imagens/placeholder.jpg') ?>" />
      <button class="seta esquerda">❮</button>
      <button class="seta direita">❯</button>
    </div>

    <!-- DESCRIÇÃO -->
    <section class="descricao">

      <!-- COLUNA ESQUERDA -->
      <div class="col-esquerda">
        <h1><?= htmlspecialchars($restaurante['nomeRestaurante']) ?></h1>
        <p class="categoria"><?= tagNome($restaurante['idTags']) ?></p>

        <h3 class="titulo-destaque">Pontos de destaque</h3>
        <ul class="lista-destaque">
          <?php
            $pontos = explode(",", $restaurante['descricao']); // separa por vírgula
            foreach ($pontos as $ponto) {
                echo "<li>" . htmlspecialchars(trim($ponto)) . "</li>";
            }
          ?>
        </ul>
      </div>

      <!-- COLUNA DIREITA -->
      <div class="col-direita">
        <div class="estrelas"><?= gerarEstrelas($restaurante['notaMedia']) ?></div>

        <div class="tags">
          <span><?= htmlspecialchars($restaurante['preco']) ?></span>
          <?php
            $caracteristicas = explode(",", $restaurante['caracteristicas']); 
            foreach ($caracteristicas as $c) {
                echo "<span>" . htmlspecialchars(trim($c)) . "</span>";
            }
          ?>
        </div>
      </div>

       <!--<a href="avaliar_restaurante.php" class="btn-avaliar">AVALIAR TAMBÉM</a>   -->
       <a href="avaliar_restaurante.php?id=<?= $restaurante['idRestaurante'] ?>&nome=<?= urlencode($restaurante['nomeRestaurante']) ?>" class="btn-avaliar">AVALIAR </a>


    </section>
  </div>

    <div class="mapa-comentarios">
      <!-- MAPA -->
      <div class="mapa-box">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8354345093747!2d144.9537353153159!3d-37.816279742013504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDQ5JzAwLjYiUyAxNDTCsDU3JzE0LjQiRQ!5e0!3m2!1spt-BR!2sbr!4v1614094615637!5m2!1spt-BR!2sbr"
          allowfullscreen="" loading="lazy">
        </iframe>
      </div>

      <div class="comentarios-section">
        <h3>Comentários</h3>

    <?php if (!empty($comentarios)): ?>
        <section class="slider">
            <?php 
            // Divide os comentários em "slides" de 3 comentários cada
            $chunks = array_chunk($comentarios, 3); 
            $slideIndex = 1;
            ?>
            
            <?php foreach ($chunks as $chunk): ?>
                <input type="radio" name="slide" id="s<?= $slideIndex ?>" <?= $slideIndex === 1 ? 'checked' : '' ?> />
                <?php $slideIndex++; ?>
            <?php endforeach; ?>

            <div class="slider-content">
                <?php foreach ($chunks as $chunk): ?>
                    <div class="slider-item">
                        <?php foreach ($chunk as $c): ?>
                            <div class="comentario">
                                <div class="usuario">
                                    <svg width="24" height="24" fill="#932C30" viewBox="0 0 24 24">
                                        <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                                    </svg> <?= htmlspecialchars($c['nomeUsuario']) ?>
                                </div>
                                <p><?= htmlspecialchars($c['descricao']) ?></p>
                                <div class="caracteristica">
                                    <p><?= htmlspecialchars($c['preco']) ?></p>
                                    <p><?= htmlspecialchars($c['caracteristicas']) ?></p>
                                </div>
                                <div class="estrela"><?= gerarEstrelas($c['notaRestaurante']) ?></div>
                                <div class="data"><?= date('d/m/Y', strtotime($c['data'] ?? date('Y-m-d'))) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="slider-nav">
                <?php for ($i = 1; $i < $slideIndex; $i++): ?>
                    <label for="s<?= $i ?>"></label>
                <?php endfor; ?>
            </div>
        </section>
    <?php else: ?>
        <p>Este restaurante ainda não possui comentários.</p>
    <?php endif; ?>
        </div>
    </div>
    </div>
    </div>
  </main>

</body>

</html>