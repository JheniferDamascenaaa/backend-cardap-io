<?php
$apiUrl = "http://localhost/cardapio-back/api/restaurante_api.php";

$restaurantes = json_decode(file_get_contents($apiUrl), true);

// Se der erro, evita quebrar a página
if (!is_array($restaurantes)) {
    $restaurantes = [];
}

// Convertendo ID da tag para nome
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

// Gerar estrelas ★★★☆☆
function gerarEstrelas($nota) {
    $cheias = round($nota);
    $html = "";
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= $cheias ? "★" : "☆";
    }
    return $html;
}
?>


<!DOCTYPE html>
<html lang="pt-BR"> 

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página Inicial</title>
    <link rel="stylesheet" href="./estilos/home-style.css" />  
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

    <!-- BARRA DE PESQUISA -->
    <div class="barra-pesquisa">
        <div class="icone-pesquisa">🔍</div>
        <input type="text" id="campo-busca" placeholder="Pesquisar">
    </div>

    <!-- LISTA DE RESTAURANTES -->
    <div class="cards-container">
  

         <?php foreach ($restaurantes as $r): ?>

        <a href="restaurante.php?id=<?= $r['idRestaurante'] ?>" class="card">

            <img src="<?= $r['imagemPrincipal'] ?: 'imagens/placeholder.jpg' ?>" 
                 alt="Imagem do restaurante" 
                 class="card-imagem">

            <div class="card-info">
                <div class="card-topo">
                    <div>
                        <h3><?= htmlspecialchars($r['nomeRestaurante']) ?></h3>
                        <strong><?= tagNome($r['idTags']) ?></strong>
                    </div>
                </div>

                <div class="caracteristica">
                    <p><?= $r['preco'] ?></p>

                    <?php 
                    $caracteristicas = explode(",", $r['caracteristicas']);
                    foreach ($caracteristicas as $c): ?>
                        <p><?= trim($c) ?></p>
                    <?php endforeach; ?>
                </div>

                <div class="estrelas"><?= gerarEstrelas($r['notaMedia']) ?></div>
            </div>
        </a>

    <?php endforeach; ?>

    </div>

    <!-- BOTÃO FLUTUANTE "+" -->
    <a href="adicionar_restaurante.php" class="btn-add">+</a>

    <!-- FILTRAGEM DA BARRA DE PESQUISA -->
<script>
function normalizar(txt) {
    return txt
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .toLowerCase();
}

document.getElementById("campo-busca").addEventListener("input", function () {
    let termoBruto = this.value.trim();
    let termo = normalizar(termoBruto);
    let cards = document.querySelectorAll(".card");

    const precoSinonimos = {
        "barato": "$",
        "economico": "$",
        "simples": "$",
        "acessivel": "$",

        "medio": "$$",
        "intermediario": "$$",

        "caro": "$$$",
        "carissimo": "$$$",
        "luxo": "$$$",
        "premium": "$$$",
        "sofisticado": "$$$"
    };

    // Detectar sinônimos digitados
    let precoBuscadoPorSinonimo = null;
    for (let palavra of termo.split(" ")) {
        if (precoSinonimos[palavra]) {
            precoBuscadoPorSinonimo = precoSinonimos[palavra];
            break;
        }
    }

    // Detectar $ digitado diretamente
    let precoBuscadoDireto = (termo.match(/\$/g) || []).join("");
    if (precoBuscadoDireto.length === 0)
        precoBuscadoDireto = null;

    cards.forEach(card => {
        let nome = normalizar(card.querySelector("h3")?.textContent || "");
        let culinaria = normalizar(card.querySelector("strong")?.textContent || "");
        let caracteristicas = [...card.querySelectorAll(".caracteristica p")]
            .map(p => normalizar(p.textContent))
            .join(" ");
        let estrelas = normalizar(card.querySelector(".estrelas")?.textContent || "");

        let precoCard = (caracteristicas.match(/\$/g) || []).join("");

        let textoCompleto = `${nome} ${culinaria} ${caracteristicas} ${estrelas}`;

        // Regra de busca
        let matchTexto = textoCompleto.includes(termo);

        let matchPreco = true;

        if (precoBuscadoPorSinonimo) {
            matchPreco = precoCard === precoBuscadoPorSinonimo;
        }

        if (precoBuscadoDireto) {
            matchPreco = precoCard === precoBuscadoDireto;
        }

        // Exibir card
        if (precoBuscadoPorSinonimo || precoBuscadoDireto) {
            card.style.display = matchPreco ? "flex" : "none";
        } else {
            card.style.display = matchTexto ? "flex" : "none";
        }
    });
});
</script>



</body>

</html>
