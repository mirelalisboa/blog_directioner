<!-- =====================================================
     pages/noticias.php — NOTÍCIAS E BLOG (comentários)
     O QUE FAZ 1: página pública das notícias — vídeo, links
     externos de notícias e o blog do banco (notícias + comentá-
     rios com formulários discretos).
     O QUE FAZ 2: permite ao usuário logado PUBLICAR notícia e
     COMENTAR — via cadastra_noticia()/adiciona_comentario().
     POR QUÊ: página central da comunidade do site.
     ===================================================== -->
<?php

// Sessão: guardas de login precisam dela; o "tipo" do POST
// decide se gravamos notícia ou comentário.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Guardas: exige login para publicar/comentar.
require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../login/verificauser.php';
// functions.php: funções de blog + $conexao.
require_once __DIR__ . '/../includes/functions.php';

// $conexao do banco (vinda por functions.php); id do usuário
// logado (o guard acima garante que existe) — usado por curtir/favoritar.
$usuario_id = (int) ($_SESSION['id'] ?? 0);

$mensagem = '';

// Salva notícia ou comentário quando o formulário é enviado
// (POST). Cada form tem um campo hidden "tipo" que decide o que gravar.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';

    try {
        // Publicar notícia (form "Adicionar notícia")
        if ($tipo === 'noticia') {
            if (cadastra_noticia($conexao, $_POST['titulo'] ?? '', $_POST['noticia'] ?? '', $_POST['foto'] ?? '', $_POST['link'] ?? '')) {
                $mensagem = 'Notícia adicionada com sucesso!';
            } else {
                $mensagem = 'Preencha o título e o texto da notícia.';
            }
        }
        // Comentar uma notícia (form de cada card)
        elseif ($tipo === 'comentario') {
            // noticia_id vem hidden do formulário do card.
            if (adiciona_comentario($conexao, $_POST['noticia_id'] ?? 0, $_POST['comentario'] ?? '')) {
                $mensagem = 'Comentário adicionado com sucesso!';
            } else {
                $mensagem = 'Selecione uma notícia válida e escreva um comentário.';
            }
        }
        // Curtir/descurtir um card (toggle por usuário)
        elseif ($tipo === 'curtir') {
            if (alternar_curtida($conexao, $_POST['noticia_id'] ?? 0, $usuario_id)) {
                $mensagem = 'Notícia curtida!';
            } else {
                $mensagem = 'Curtida removida.';
            }
        }
        // Favoritar/desfavoritar um card (toggle por usuário)
        elseif ($tipo === 'favoritar') {
            if (alternar_favorito($conexao, $_POST['noticia_id'] ?? 0, $usuario_id)) {
                $mensagem = 'Notícia favoritada! Aparece no seu perfil.';
            } else {
                $mensagem = 'Favorito removido.';
            }
        }
    } catch (PDOException $e) {
        $mensagem = 'Erro ao salvar. Verifique os dados e tente novamente.';
    }
}

// Notícias do banco (mais recentes primeiro), cada uma com
// seus comentários (buscados no loop de exibição).
$noticias = listar_noticias($conexao);
$total = count($noticias);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Direction</title>

<style>
    /* =========================================================
       PALETA DE CORES E VARIÁVEIS
       ========================================================= */
    :root {
        --fundo: #1C1C1C;
        --card: #343D3A;
        --azul: #587987;
        --azul-claro: #A9C4CE;
        --marrom: #7C482D;
        --marrom-claro: #A56847;
        --cinza: #788589;
        --texto: #E7E2DA;
        --texto-suave: #B6B5AF;
    }

    /* =========================================================
       VISUAL GERAL
       ========================================================= */
    body {
        background-color: var(--fundo);
        color: var(--texto);
        font-family: Georgia, 'Times New Roman', Times, serif;
        margin: 0;
        padding: 10px 15px;
    }

    /* Títulos e elementos globais */
    h1 {
        color: var(--texto);
        text-align: center;
        font-size: 1.7em;
        margin: 20px 0;
    }

    h2 {
        color: var(--azul-claro);
        text-align: center;
        font-size: 1.35em;
        margin: 20px auto 8px;
    }

    /* Separadores */
    hr {
        max-width: 760px;
        border: none;
        height: 1px;
        background: linear-gradient(
            to right,
            transparent,
            var(--azul),
            var(--marrom),
            transparent
        );
        margin: 24px auto;
    }

    /* Mensagens gerais */
    .mensagem {
        max-width: 600px;
        margin: 12px auto;
        padding: 8px 12px;
        border: 1px solid var(--cinza);
        border-radius: 7px;
        background-color: var(--card);
        color: var(--texto);
        font-size: 0.85em;
        text-align: center;
    }

    /* =========================================================
       VÍDEO CENTRALIZADO
       ========================================================= */
    .video-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        max-width: 1000px;
        margin: 22px auto 28px;
        padding: 0 12px;
    }

    video {
        display: block;
        width: 100%;
        max-width: 900px;
        height: auto;
        margin: 0 auto;
        border: 2px solid var(--azul);
        border-radius: 12px;
        background-color: var(--fundo);
        box-shadow: 
            0 6px 18px rgba(0, 0, 0, 0.35),
            0 0 12px rgba(88, 121, 135, 0.12);
    }

    /* =========================================================
       ESTRUTURA DO BLOG E CARDS
       ========================================================= */
    .blog {
        width: 100%;
        max-width: 600px;
        margin: 12px auto;
    }

    .blog h2 {
        color: var(--azul-claro);
        font-size: 1.45em;
        text-align: center;
        margin-bottom: 14px;
    }

    .card {
        background-color: var(--card);
        border: 1px solid rgba(120, 133, 137, 0.55);
        border-radius: 9px;
        padding: 12px 15px;
        margin: 12px 0;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.16);
    }

    .blog h3 {
        color: var(--texto);
        font-size: 1.02em;
        line-height: 1.4;
        margin-bottom: 5px;
    }

    .blog .data {
        color: var(--texto-suave);
        font-size: 0.74em;
        margin-bottom: 5px;
    }

    .blog p, 
    body > p {
        color: var(--texto-suave);
        font-size: 0.86em;
        line-height: 1.55;
        margin: 8px auto 14px;
    }

    .card p {
        color: var(--texto);
        margin: 5px 0;
    }

    .blog img {
        display: block;
        max-width: 100%;
        max-height: 300px;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 7px;
        margin: 10px auto;
    }

    .card a {
        color: var(--azul-claro);
        font-size: 0.88em;
        text-decoration: none;
    }

    .card a:hover {
        color: var(--marrom-claro);
        text-decoration: underline;
    }

    /* =========================================================
       FORMULÁRIOS, BOTÕES E INTERAÇÃO
       ========================================================= */
    /* Botões de ação genéricos */
    input[type="button"] {
        display: block;
        margin: 12px auto 16px;
        padding: 8px 18px;
        border: 1px solid var(--azul);
        border-radius: 20px;
        background-color: var(--card);
        color: var(--texto);
        font-family: inherit;
        font-size: 0.84em;
        cursor: pointer;
        transition: 0.2s ease;
    }

    input[type="button"]:hover {
        background-color: var(--azul);
        color: var(--texto);
    }

    /* Caixas colapsáveis */
    details {
        max-width: 680px;
        margin: 10px auto;
        padding: 9px 13px;
        border: 1px solid rgba(120, 133, 137, 0.55);
        border-radius: 8px;
        background-color: var(--card);
    }

    details summary {
        color: var(--azul-claro);
        font-size: 0.86em;
        cursor: pointer;
    }

    /* Campos de texto */
    textarea {
        box-sizing: border-box;
        width: 100%;
        padding: 8px 10px;
        margin-top: 7px;
        border: 1px solid var(--azul);
        border-radius: 6px;
        background-color: #252B2A;
        color: var(--texto);
        font-family: inherit;
        font-size: 0.88em;
        resize: vertical;
    }

    textarea:focus {
        outline: 1px solid var(--azul-claro);
    }

    /* Botão enviar comentário */
    input[type="submit"] {
        margin-top: 8px;
        padding: 7px 17px;
        border: none;
        border-radius: 20px;
        background-color: var(--marrom);
        color: var(--texto);
        font-family: inherit;
        font-size: 0.83em;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: var(--marrom-claro);
    }

    /* Curtidas e Favoritos */
    .acoes {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
        margin: 10px auto;
    }

    .acoes form {
        margin: 0;
    }

    .btn-curtir,
    .btn-favoritar {
        padding: 5px 12px;
        border: 1px solid var(--azul);
        border-radius: 20px;
        background-color: var(--card);
        color: var(--texto-suave);
        font-family: inherit;
        font-size: 0.78em;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .btn-curtir:hover,
    .btn-favoritar:hover {
        background-color: var(--azul);
        color: var(--texto);
    }

    /* Estado ativo para botões */
    .btn-curtir.ativo,
    .btn-favoritar.ativo,
    .ativo {
        border-color: var(--marrom-claro) !important;
        background-color: #49362E !important;
        color: #E5B99A !important;
        font-weight: bold;
    }

    /* =========================================================
       SEÇÃO DE COMENTÁRIOS
       ========================================================= */
    .comentarios {
        margin-top: 10px;
        padding-top: 7px;
        border-top: 1px solid rgba(120, 133, 137, 0.45);
    }

    .comentarios summary {
        color: var(--texto-suave);
        font-size: 0.82em;
        cursor: pointer;
    }

    .comentarios p {
        margin: 6px 0 6px 8px;
        padding-left: 9px;
        border-left: 2px solid var(--azul);
        color: var(--texto);
        font-size: 0.84em;
        line-height: 1.5;
    }

    /* =========================================================
       RODAPÉ E ADAPTAÇÃO MOBILE
       ========================================================= */
    footer {
        text-align: center;
    }

    @media (max-width: 600px) {
        body {
            padding: 10px;
        }

        .video-container {
            margin: 16px auto 22px;
            padding: 0 4px;
        }

        video {
            max-width: 100%;
            border-radius: 8px;
            border-width: 1px;
        }

        h1 {
            font-size: 1.45em;
        }

        h2, 
        .blog h2 {
            font-size: 1.25em;
        }

        .blog {
            max-width: 100%;
        }

        .card {
            padding: 10px 12px;
        }

        body > p, 
        .blog p {
            font-size: 0.84em;
        }
    }
</style>

</head>

<body>

    <!-- Menu global -->
 <?php include __DIR__ . '/../includes/header.php'; ?>
<hr><br>
    <!-- Vídeo de abertura (a pasta tem nomes estranhos — não renomear) -->
    <video width="1000" controls autoplay muted>
          <source src="..\videos\video.mp4\videoonet.mp4.png.mp4.mp4" type="video/mp4">
      </video>


<!-- ================= NOTÍCIAS E COMENTÁRIOS DO BLOG =================
     (conteúdo vindo do banco: noticias + comentarios) -->

<div class="blog">

    <h2 style="color:#B4C4D4; font-size:1.7em; text-align:center;">Notícias do Blog</h2>

    <!-- Status da última operação (publicar/comentar) -->
    <?php if ($mensagem !== ''): ?>
        <p class="mensagem"><?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <!-- Publicar (recolhido por padrão) -->
    <details>
        <summary>+ Adicionar notícia</summary>

        <form method="POST" action="#">
            <input type="hidden" name="tipo" value="noticia">

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>

            <label for="noticia_txt">Notícia:</label>
            <textarea name="noticia" id="noticia_txt" rows="3" required></textarea>

            <label for="foto">Foto (endereço da imagem):</label>
            <input type="text" name="foto" id="foto">

            <label for="link">Link da notícia original:</label>
            <input type="url" name="link" id="link">

            <input type="submit" value="Publicar">
        </form>
    </details>

    <?php if ($total === 0): ?>
        <p style="color:#8b8b8b; font-size:0.88em;">Nenhuma notícia publicada ainda.</p>
    <?php endif; ?>

    <!-- Um card por notícia, cada uma com seus comentários.
         id="noticia-X": âncora para o atalho "Ver no blog"
         vindo do perfil -->
    <?php foreach ($noticias as $item): ?>
        <div class="card" id="noticia-<?php echo (int) $item['id']; ?>">
            <h3><?php echo htmlspecialchars($item['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>

            <?php if (!empty($item['data_publicacao'])): ?>
                <span class="data"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($item['data_publicacao']))); ?></span>
            <?php endif; ?>

            <p><?php echo nl2br(htmlspecialchars($item['noticia'], ENT_QUOTES, 'UTF-8')); ?></p>

            <?php if (!empty($item['foto'])): ?>
                <img src="<?php echo htmlspecialchars($item['foto'], ENT_QUOTES, 'UTF-8'); ?>" alt="foto da notícia" width="180">
            <?php endif; ?>

            <?php if (!empty($item['link'])): ?>
                <p><a href="<?php echo htmlspecialchars($item['link'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">Ver original &rsaquo;</a></p>
            <?php endif; ?>

            <?php
            // Contagem de curtidas + estado do usuário logado
            // (pinta os botões quando ele já curtiu/favoritou).
            $curtidas  = contar_curtidas($conexao, $item['id']);
            $curtiu    = usuario_curtiu($conexao, $item['id'], $usuario_id);
            $favoritou = usuario_favoritou($conexao, $item['id'], $usuario_id);
            ?>

            <!-- Curtir + Favoritar: cada card tem seus formulários
                 com input hidden noticia_id; o POST recarrega a
                 página já mostrando o novo estado -->
            <div class="acoes">
                <form method="POST" action="#">
                    <input type="hidden" name="tipo" value="curtir">
                    <input type="hidden" name="noticia_id" value="<?php echo (int) $item['id']; ?>">

                    <button type="submit" class="btn-curtir<?php echo $curtiu ? ' ativo' : ''; ?>">
                        &hearts; Curtir (<?php echo $curtidas; ?>)
                    </button>
                </form>

                <form method="POST" action="#">
                    <input type="hidden" name="tipo" value="favoritar">
                    <input type="hidden" name="noticia_id" value="<?php echo (int) $item['id']; ?>">

                    <button type="submit" class="btn-favoritar<?php echo $favoritou ? ' ativo' : ''; ?>">
                        <?php echo $favoritou ? '&starf; Favoritada' : '&star; Favoritar'; ?>
                    </button>
                </form>
            </div>

            <?php $comentarios = listar_comentarios($conexao, $item['id']); ?>

            <!-- Comentários recolhidos: abrem no clique; o
                 contador mostra quantos existem -->
            <details class="comentarios">
                <summary>Comentários (<?php echo count($comentarios); ?>)</summary>

                <?php foreach ($comentarios as $c): ?>
                    <p>&laquo;<?php echo nl2br(htmlspecialchars($c['comentario'], ENT_QUOTES, 'UTF-8')); ?>&raquo;</p>
                <?php endforeach; ?>
                <?php if (empty($comentarios)): ?>
                    <p style="color:#8b8b8b;">Nenhum comentário ainda.</p>
                <?php endif; ?>

                <!-- Formulário de comentário: o campo hidden
                     noticia_id identifica a notícia do card -->
                <form method="POST" action="#" class="form-comentario">
                    <input type="hidden" name="tipo" value="comentario">
                    <input type="hidden" name="noticia_id" value="<?php echo (int) $item['id']; ?>">

                    <textarea name="comentario" rows="2" placeholder="Escreva um comentário..." required></textarea>

                    <input type="submit" value="Comentar">
                </form>
            </details>
        </div>
    <?php endforeach; ?>

</div>

</body>
<hr>
 <!-- Rodapé global -->
 <?php include __DIR__ . '/../includes/footer.php'; ?>

</html>
