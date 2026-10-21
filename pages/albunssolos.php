<!-- =====================================================
     pages/albunssolos.php — ÁLBUNS SOLO
     O QUE FAZ: um bloco por integrante (Harry, Zayn, Niall,
     Louis, Liam) com os álbuns solo e atalho para o YouTube.

     ===================================================== -->
<?php

// Álbuns solo, um bloco por integrante — dados em variáveis,
// HTML abaixo só exibe (mesma técnica de pages/albuns.php).
$harry1 = "Harry Styles (2017)";
$harry2 = "Fine Line (2019)";
$harry3 = "Harry's House (2022)";
$harry4 = "Kiss All the Time. Disco, Occasionally (2026)";

$zayn1 = "Mind of Mine (2016)";
$zayn2 = "Icarus Falls (2018)";
$zayn3 = "Nobody Is Listening (2021)";
$zayn4 = "Konnnacol (2026)";


$niall1 = "Flicker (2017)";
$niall2 = "Heartbreak Weather (2020)";
$niall3 = "The Show (2023)";
$niall4 = "Dinner Party (2026)";

$louis1 = "Walls (2020)";
$louis2 = "Faith in the Future (2022)";
$louis3 = "How Did I Get Here? (2026)";

$liam1 = "LP1 (2019)";

// Guardas de login (mesma exigência de pages/albuns.php).
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
        } elseif ($tipo === 'curtir') {
            if (alternar_curtida_album($conexao, $_POST['album_id'] ?? 0, $usuario_id)) {
                $mensagem = 'Álbum curtido!';
            } else {
                $mensagem = 'Curtida removida.';
            }
        } elseif ($tipo === 'favoritar') {
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

// Registros dos álbuns solo no banco (UM CARD POR INTEGRANTE),
// indexados pelo título/h2 de cada bloco. Se o banco ainda
// não tiver o cadastro (database/albuns.sql), os blocos de
// interação simplesmente não aparecem — a página fica igual.
$albuns_por_titulo = [];
foreach (listar_albuns($conexao, 'solo') as $linha) {
    $albuns_por_titulo[$linha['titulo']] = $linha;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Direction</title>

    <!-- Só o CSS das interações — valores EXATOS do blog de
         notícias (pages/noticias.php), para os bloquinhos
         ficarem iguais aos cartões de notícia -->
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
        body>p {
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
            background: linear-gradient(to right,
                    transparent,
                    var(--azul),
                    var(--marrom),
                    transparent);
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

        /* Link de voltar - afeta APENAS este botão */
        a.btn-voltar {
            display: table;
            margin: 20px auto;
            padding: 8px 20px;
            border: 1px solid var(--azul);
            border-radius: 20px;
            background-color: var(--card);
            color: var(--texto);
            text-decoration: none;
            font-size: 0.85em;
            transition: 0.2s ease;
        }

        a.btn-voltar:hover {
            background-color: var(--azul);
            color: var(--texto);
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

            body>p {
                font-size: 0.86em;
            }
        }

        /* Estilização exclusiva para o link de voltar no final da página */
        a.voltar-albuns,
        a.voltar-albuns:visited {
            display: block;
            width: fit-content;
            margin: 25px auto;
            color: var(--azul-claro);
            text-decoration: none;
            font-size: 0.95em;
            font-weight: bold;
            transition: color 0.2s ease;
        }

        a.voltar-albuns:hover {
            color: var(--texto);
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Menu global -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <video width="1000" controls autoplay muted>
        <source src="..\videos\video.mp4\videonight.mp4.png.mp4.mp4" type="video/mp4">
    </video>



    <h1>Álbuns Solo</h1>

    <!-- Status da última operação (comentar/curtir/favoritar) -->
    <?php if ($mensagem !== ''): ?>
        <p class="mensagem"><?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <hr>

    <!-- ==== Um bloco por integrante (mesmo molde: h2 + parágrafo
         + botão de busca no YouTube + hr) ==== -->
    <h2>Harry Styles</h2>

    <p>
        <?php echo $harry1; ?>, <?php echo $harry2; ?> e <?php echo $harry3; ?> <?php echo $harry4; ?>.
        Após a pausa do grupo, Harry lançou carreira solo de sucesso,
        com destaque para "Sign of the Times", "Watermelon Sugar" e "As It Was".
    </p>

    <input type="button" value="Ouvir no YouTube"
        onclick="window.location.href='https://www.youtube.com/results?search_query=Harry+Styles+albums'">

    <!-- Interações deste bloco (curtir/favoritar/comentar);
         o registro é achado pelo nome do integrante -->
    <?php interacoes_album($conexao, $albuns_por_titulo['Harry Styles'] ?? null, $usuario_id); ?>

    <hr>

    <h2>Zayn Malik</h2>

    <p>
        <?php echo $zayn1; ?>, <?php echo $zayn2; ?> e <?php echo $zayn3; ?> <?php echo $zayn4; ?>.
        Zayn foi o primeiro a sair do grupo, em 2015, e estreou em carreira solo
        com o hit "Pillowtalk", seguindo com um estilo mais R&B alternativo.
    </p>

    <input type="button" value="Ouvir no YouTube"
        onclick="window.location.href='https://www.youtube.com/results?search_query=Zayn+albums'">

    <?php interacoes_album($conexao, $albuns_por_titulo['Zayn Malik'] ?? null, $usuario_id); ?>

    <hr>

    <h2>Niall Horan</h2>

    <p>
        <?php echo $niall1; ?>, <?php echo $niall2; ?> e <?php echo $niall3; ?> <?php echo $niall4; ?>.
        Niall seguiu no estilo pop/folk, com sucessos como "Slow Hands",
        "Nice To Meet Ya" e "Heaven".
    </p>

    <input type="button" value="Ouvir no YouTube"
        onclick="window.location.href='https://www.youtube.com/results?search_query=Niall+Horan+albums'">

    <?php interacoes_album($conexao, $albuns_por_titulo['Niall Horan'] ?? null, $usuario_id); ?>

    <hr>

    <h2>Louis Tomlinson</h2>

    <p>
        <?php echo $louis1; ?> e <?php echo $louis2; ?> <?php echo $louis3; ?>.
        Louis apostou em um som mais indie rock, com músicas como
        "Walls", "Kill My Mind" e "Out of My System".
    </p>

    <input type="button" value="Ouvir no YouTube"
        onclick="window.location.href='https://www.youtube.com/results?search_query=Louis+Tomlinson+albums'">

    <?php interacoes_album($conexao, $albuns_por_titulo['Louis Tomlinson'] ?? null, $usuario_id); ?>

    <hr>

    <h2>Liam Payne</h2>

    <p>
        <?php echo $liam1; ?>.
        Liam lançou seu álbum único em carreira solo, com
        destaque para "Strip That Down", "Familiar" e "For You", Feat com Rita Ora.
    </p>

    <input type="button" value="Ouvir no YouTube"
        onclick="window.location.href='https://www.youtube.com/results?search_query=Liam+Payne+albums'">

    <?php interacoes_album($conexao, $albuns_por_titulo['Liam Payne'] ?? null, $usuario_id); ?>

    <hr>

    <!-- Volta para a página dos álbuns do grupo -->
    <a href="albuns.php" class="voltar-albuns">Voltar para os álbuns do grupo</a>

    <br>

    <!-- Rodapé global -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>