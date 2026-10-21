<!-- =====================================================
     pages/albuns.php — ÁLBUNS DA BANDA
     O QUE FAZ: lista os 5 álbuns de estúdio do One Direction
     com descrição curta e atalho para ouvir no YouTube.
     COMPLEMENTO: cada bloco também tem curtir/favoritar/
     comentários (a mesma interação do blog de notícias),
     ligada à tabela albuns do banco (script database/
     albuns.sql) via includes/functions.php.
     POR QUÊ: página pública de consulta, no padrão visual do
     site (protegida por login: requere sessão ativa).
     ===================================================== -->
<?php

// Nomes/ordem dos álbuns em variáveis: a lógica PHP separa
// dados do layout (o HTML abaixo só exibe as variáveis).
$album1 = "Up All Night";
$album2 = "Take Me Home";
$album3 = "Midnight Memories";
$album4 = "FOUR";
$album5 = "Made In The A.M.";

// Guardas de login (incluem o session_start()).
require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../login/verificauser.php';
// functions.php: $conexao + curtir/favoritar/comentar álbuns
// (mesma mecânica das notícias).
require_once __DIR__ . '/../includes/functions.php';

// id do usuário logado (o guard acima garante que existe) —
// usado por curtir/favoritar.
$usuario_id = (int) ($_SESSION['id'] ?? 0);

$mensagem = '';

// Salva comentário/curtida/favorito quando um formulário dos
// blocos é enviado (POST). Cada form traz o campo hidden
// "tipo" (a ação) e "album_id" (o álbum do bloco).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';

    try {
        if ($tipo === 'comentario') {
            if (adiciona_comentario_album($conexao, $_POST['album_id'] ?? 0, $_POST['comentario'] ?? '')) {
                $mensagem = 'Comentário adicionado com sucesso!';
            } else {
                $mensagem = 'Selecione um álbum válido e escreva um comentário.';
            }
        }
        elseif ($tipo === 'curtir') {
            if (alternar_curtida_album($conexao, $_POST['album_id'] ?? 0, $usuario_id)) {
                $mensagem = 'Álbum curtido!';
            } else {
                $mensagem = 'Curtida removida.';
            }
        }
        elseif ($tipo === 'favoritar') {
            if (alternar_favorito_album($conexao, $_POST['album_id'] ?? 0, $usuario_id)) {
                $mensagem = 'Álbum favoritado! Aparece no seu perfil.';
            } else {
                $mensagem = 'Favorito removido.';
            }
        }
    } catch (PDOException $e) {
        $mensagem = 'Erro ao salvar. Verifique os dados e tente novamente.';
    }
}

// Registros dos álbuns da banda no banco, indexados pelo
// título: cada bloco abaixo busca o seu. Se o banco ainda
// não tiver o cadastro (database/albuns.sql), os blocos de
// interação simplesmente não aparecem — a página fica igual.
$albuns_por_titulo = [];
foreach (listar_albuns($conexao, 'banda') as $linha) {
    $albuns_por_titulo[$linha['titulo']] = $linha;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Direction</title>

<style>
    /* Paleta compartilhada com noticias.php */
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

    /* Visual geral */
    body {
        background-color: var(--fundo);
        color: var(--texto);
        font-family: Georgia, 'Times New Roman', Times, serif;
        margin: 0;
        padding: 10px 15px;
    }

    /* Vídeo grande e centralizado */
    video {
        display: block;
        width: 100%;
        max-width: 900px;
        height: auto;
        margin: 22px auto 28px;
        border: 2px solid var(--azul);
        border-radius: 12px;
        background-color: var(--fundo);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35);
    }

    /* Título principal */
    h1 {
        color: var(--texto);
        text-align: center;
        font-size: 1.7em;
        margin: 20px 0;
    }

    /* Títulos dos álbuns */
    h2 {
        color: var(--azul-claro);
        text-align: center;
        font-size: 1.35em;
        margin: 20px auto 8px;
    }

    /* Descrições dos álbuns */
    body > p {
        max-width: 720px;
        margin: 8px auto 14px;
        color: var(--texto-suave);
        font-size: 0.91em;
        line-height: 1.55;
        text-align: center;
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

    /* Botões para ouvir no YouTube */
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

    /* Mensagens */
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

    /* Caixas de interação */
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

    /* Campo de comentário */
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

    /* Botão de comentar */
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

    /* Curtidas e favoritos */
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

    /* Estado ativo sem fundo ou borda branca */
    .btn-curtir.ativo,
    .btn-favoritar.ativo,
    .ativo {
        border-color: var(--marrom-claro) !important;
        background-color: #49362E !important;
        color: #E5B99A !important;
        font-weight: bold;
    }

    /* Comentários */
    .comentarios {
        margin-top: 10px;
        padding-top: 7px;
        border-top: 1px solid rgba(120, 133, 137, 0.45);
    }

    .comentarios summary {
        color: var(--texto-suave);
        font-size: 0.82em;
    }

    .comentarios p {
        margin: 6px 0 6px 8px;
        padding-left: 9px;
        border-left: 2px solid var(--azul);
        color: var(--texto);
        font-size: 0.84em;
        line-height: 1.5;
    }

    footer {
        text-align: center;
    }

    /* Adaptação para celulares */
    @media (max-width: 600px) {
        body {
            padding: 10px;
        }

        video {
            margin: 16px auto 22px;
            border-width: 1px;
            border-radius: 8px;
        }

        h1 {
            font-size: 1.45em;
        }

        h2 {
            font-size: 1.15em;
        }

        body > p {
            font-size: 0.86em;
        }
    }
</style>

</head>
<!-- Menu global -->
<?php include __DIR__ . '/../includes/header.php'; ?>

  <video width="1000" controls autoplay muted>
        <source src="..\videos\video.mp4\videoanother.mp4.png.mp4.mp4" type="video/mp4">
    </video>
<body>


    <h1>Álbuns</h1>

    <!-- Status da última operação (comentar/curtir/favoritar) -->
    <?php if ($mensagem !== ''): ?>
        <p class="mensagem"><?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <hr>

    <!-- ==== Bloco de cada álbum: título do banco de variáveis,
         texto fixo de conteúdo e botão de busca no YouTube ==== -->
    <h2>
        <?php echo $album1; ?>
    </h2>

    <p>
        Primeiro álbum de estúdio do One Direction, lançado em 2011.
        Foi o álbum que marcou o início da carreira musical do grupo e
        trouxe músicas que ficaram muito conhecidas, como What Makes You Beautiful
        e One Thing.
    </p>

    <!-- onclick abre a busca do YouTube em outra aba -->
    <input type="button" value="Ouvir no YouTube"
    onclick="window.location.href='https://www.youtube.com/results?search_query=One+Direction+Up+All+Night+album'">

    <!-- Interações deste álbum (curtir/favoritar/comentar);
         o registro é achado pelo título do bloco -->
    <?php interacoes_album($conexao, $albuns_por_titulo[$album1] ?? null, $usuario_id); ?>

    <hr>


    <h2>
        <?php echo $album2; ?>
    </h2>

    <p>
        Segundo álbum do grupo, lançado em 2012. O álbum continuou o sucesso
        do One Direction e trouxe músicas como Live While We're Young e Little Things.
    </p>

    <input type="button" value="Ouvir no YouTube"
    onclick="window.location.href='https://www.youtube.com/results?search_query=One+Direction+Take+Me+Home+album'">

    <?php interacoes_album($conexao, $albuns_por_titulo[$album2] ?? null, $usuario_id); ?>

    <hr>


    <h2>
        <?php echo $album3; ?>
    </h2>

    <p>
        Terceiro álbum do One Direction, lançado em 2013. O disco mostrou
        uma evolução no estilo musical do grupo e trouxe músicas como
        Best Song Ever, Story of My Life e Midnight Memories.
    </p>

    <input type="button" value="Ouvir no YouTube"
    onclick="window.location.href='https://www.youtube.com/results?search_query=One+Direction+Midnight+Memories+album'">

    <?php interacoes_album($conexao, $albuns_por_titulo[$album3] ?? null, $usuario_id); ?>

    <hr>


    <h2>
        <?php echo $album4; ?>
    </h2>

    <p>
        Quarto álbum do grupo, lançado em 2014. FOUR trouxe uma sonoridade
        mais madura e músicas como Steal My Girl, Night Changes e Fireproof.
    </p>

    <input type="button" value="Ouvir no YouTube"
    onclick="window.location.href='https://www.youtube.com/results?search_query=One+Direction+FOUR+album'">

    <?php interacoes_album($conexao, $albuns_por_titulo[$album4] ?? null, $usuario_id); ?>

    <hr>


    <h2>
        <?php echo $album5; ?>
    </h2>

    <p>
        Quinto e último álbum de estúdio do One Direction, lançado em 2015.
        O álbum inclui músicas como Drag Me Down, Perfect e History.
    </p>

    <input type="button" value="Ouvir no YouTube"
    onclick="window.location.href='https://www.youtube.com/results?search_query=One+Direction+Made+In+The+AM+album'">

    <?php interacoes_album($conexao, $albuns_por_titulo[$album5] ?? null, $usuario_id); ?>

    <hr>

    <!-- Rodapé global -->
    <?php include  __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>
