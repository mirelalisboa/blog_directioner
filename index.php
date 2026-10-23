<?php
// a sessao tem que comecar antes do html,
// senao o header (menu) da erro ao ler $_SESSION
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Direction</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Federo&display=swap');

    /* paleta de cores do site */
    :root {
        /* Paleta 1: Azul e Branco */
        --p1-blue: #1769AA;
        --p1-light-blue: #87CEEB;
        --p1-white: #FFFFFF;

        /* Paleta 2: Preto e Branco */
        --p2-black: #111111;
        --p2-dark-gray: #555555;
        --p2-white: #FFFFFF;

        /* Paleta 3: Vermelho e Preto */
        --p3-dark-red: #C62828;
        --p3-red: #E53935;
        --p3-black: #171717;

        /* Paleta 4: Azul-marinho, Cinza e Branco */
        --p4-navy: #142B4A;
        --p4-slate-gray: #708090;
        --p4-ice-white: #E8EDF2;

        /* variaveis do tema (paleta usada no site) */
        --fundo: var(--p4-navy);           /* Fundo geral da página */
        --card: #1C365D;                  /* Fundo dos cartões/caixas */
        --azul: var(--p4-slate-gray);     /* Bordas e destaques primários */
        --azul-claro: #A9C4CE;            /* Links e títulos secundários */
        --marrom: #2B4566;                /* Botões de confirmação/comentar */
        --marrom-claro: #385B87;          /* Hover dos botões */
        --cinza: var(--p4-slate-gray);    /* Bordas de mensagens/detalhes */
        --texto: var(--p4-ice-white);     /* Texto principal */
        --texto-suave: #B0C0D0;           /* Descrições e textos secundários */
    }

    /* Reset básico */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Visual geral */
    body {
        background-color: var(--fundo);
        color: var(--texto);
        font-family: Georgia, 'Times New Roman', Times, serif;
        margin: 0;
        padding: 10px 15px;
    }

    /* Classe específica para centralizar os links sem afetar o header */
    .links-centralizados {
        text-align: center;
        margin: 20px auto;
    }

    .links-centralizados a {
        display: block;
        text-align: center;
        color: var(--azul-claro);
        font-size: 1.1em;
        margin: 8px auto;
        text-decoration: underline;
        transition: color 0.2s ease;
    }

    .links-centralizados a:hover {
        color: var(--texto);
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
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.45);
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
        height: 2px;
        background: linear-gradient(
            to right,
            transparent,
            var(--azul),
            var(--texto-suave),
            transparent
        );
        margin: 24px auto;
    }

    /* Botões para ouvir no YouTube e botões interativos */
    input[type="button"],
    button {
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
        transition: all 0.2s ease;
    }

    input[type="button"]:hover,
    button:hover {
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
        border: 1px solid var(--cinza);
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
        background-color: #0E1E34;
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
        border: 1px solid var(--azul);
        border-radius: 20px;
        background-color: var(--marrom);
        color: var(--texto);
        font-family: inherit;
        font-size: 0.83em;
        cursor: pointer;
        transition: 0.2s ease;
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

    /* Estado ativo */
    .btn-curtir.ativo,
    .btn-favoritar.ativo,
    .ativo {
        border-color: var(--azul-claro) !important;
        background-color: var(--marrom-claro) !important;
        color: var(--texto) !important;
        font-weight: bold;
    }

    /* Comentários */
    .comentarios {
        margin-top: 10px;
        padding-top: 7px;
        border-top: 1px solid var(--cinza);
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

    /* Estiliza os links do corpo da página (ignora o header) */
    main a,
    .conteudo a,
    body > a,
    body > div a {
        color: var(--azul-claro) !important;
        text-decoration: none;
        font-size: 0.95em;
        font-weight: bold;
        display: block;
        margin: 6px auto;
        text-align: center;
        transition: color 0.2s ease;
    }

    /* Evita que o navegador deixe o link roxo após ser clicado */
    main a:visited,
    .conteudo a:visited,
    body > a:visited,
    body > div a:visited {
        color: var(--azul-claro) !important;
    }

    /* Efeito ao passar o mouse */
    main a:hover,
    .conteudo a:hover,
    body > a:hover,
    body > div a:hover {
        color: var(--texto) !important;
        text-decoration: underline;
    }
</style>
<body>
    <h1> BEM VINDOS,
        AO BLOG DIRECTIONER!</h1>
    <hr>
    
    
    <!-- video de abertura do site (o nome estranho do arquivo
         e assim mesmo, nao mexi pra nao quebrar) -->
    <video controls autoplay muted>
        <source src="videos\video.mp4\videolive.mp4.png.mp4.mp4" type="video/mp4">
    </video>


   
    <hr>

    <main>
        <!-- botao que leva pra pagina de criar conta -->
        <form action="" method="post">

            <button type="button" onclick="window.location.href='/projetointegrador/login/login.php'">
                Continuar
            </button>
        </form>
    </main>


    <hr>
    <!-- rodape do site -->
    <?php include __DIR__ . '/includes/footer.php';
    ?>
</body>

</html>