<!-- os 5 integrantes: video, texto e links de noticias/albuns -->
<?php

// nomes dos integrantes em variaveis (o html so exibe)
$integrante1 = "Harry Styles";
$integrante2 = "Louis Tomlinson";
$integrante3 = "Niall Horan";
$integrante4 = "Zayn Malik";
$integrante5 = "Liam Payne";

// exige login
require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../login/verificauser.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> One Direction</title>

</head>

<body>
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

        /* Estiliza os links do corpo da página (ignora os links do header) */
        main a,
        .conteudo a,
        body>a,
        body>div a {
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
        body>a:visited,
        body>div a:visited {
            color: var(--azul-claro) !important;
        }

        /* Efeito ao passar o mouse */
        main a:hover,
        .conteudo a:hover,
        body>a:hover,
        body>div a:hover {
            color: var(--texto) !important;
            text-decoration: underline;
        }
    </style>


    <!-- Menu global -->
    <?php include __DIR__ . '/../includes/header.php'; ?>





    <h1>Integrantes do One Direction</h1>

    <hr>

    <!-- ================== HARRY (integrante1) ================== -->
    <div class="gif">
        <h2>
            <?php echo $integrante1; ?>
        </h2>
        <video autoplay loop muted playsinline width="50%">
            <source src="..\videos\video.mp4\videoharry.mp4.png.mp4.mp4" type="video/mp4">
            O seu navegador não suporta a reprodução de vídeo.
        </video>
    </div>


    <p>
        Conhecido por seu carisma, vocal marcante e estilo visual único. Após a pausa da banda, consolidou-se como um dos maiores nomes do pop atual, conquistando prêmios Grammy e estrelando filmes em Hollywood.
    </p>



    <!-- Links externos: outra aba para não perder o site -->
    <a href="https://www.rte.ie/entertainment/2026/0705/1581860-harry-styles-pays-tribute-to-one-direction-bandmates/" target="_blank">
        Ler notícia <br>
    </a>
    <a href="albunssolos.php">
        ver albuns
    </a>
    <hr>

    <!-- ================== LOUIS (integrante2) ================== -->
    <div class="gif">
        <h2>
            <?php echo $integrante2; ?>
        </h2>
        <video width="700" controls autoplay muted>
            <source src="..\videos\video.mp4\videolouis.mp4.png.mp4.mp4" type="video/mp4">
        </video>
    </div>

    <p>
        Teve papel fundamental nos bastidores, sendo um dos compositores mais assíduos do repertório da banda. Em solo, adotou uma identidade fortemente influenciada pelo indie rock e britpop.
    </p>





    <a href="https://louis-tomlinson.com/" target="_blank">
        Ver novidades
    </a> <br>
    <a href="albunssolos.php">
        ver albuns
    </a>


    <hr>
    <h2>
        <?php echo $integrante3; ?>
    </h2>

    <!-- ================== NIALL (integrante3) ================== -->
    <div class="gif">
        <video width="700" controls autoplay muted>
            <source src="..\videos\video.mp4\videoniall.mp4.png.mp4.mp4" type="video/mp4">
        </video>
    </div>

    <p>
        O único integrante irlandês do grupo, marcava presença tocando violão e com seu tom de voz suave. Em carreira solo, apostou em uma sonoridade voltada ao folk, pop acústico e soft rock, mantendo forte conexão com os fãs
    </p>



    <a href="https://portalpopline.com.br/membros-one-direction-albuns-2026-desempenho/" target="_blank">
        Ler notícia <br>
    </a>
    <a href="albunssolos.php">
        ver albuns
    </a>

    <hr>



    <h2>
        <?php echo $integrante4; ?>
    </h2>
    <!-- ================== ZAYN (integrante4) ================== -->
    <div class="gif">
        <video width="500" controls autoplay muted>
            <source src="..\videos\video.mp4\videozayn.mp4.png.mp4.mp4" type="video/mp4">
        </video>

    </div>
    <p>
        Destacava-se na banda por seu alcance vocal e pelos agudos potentes (high notes). Foi o primeiro a deixar o grupo, em março de 2015, e seguiu uma carreira solo focada no R&B alternativo e pop intimista.
    </p>


    <a href="https://www.inzayn.com/tour/#/" target="_blank">
        Ler notícias
    </a> <br>
    <a href="albunssolos.php" class="link-login">Ver Albúns</a>

    <hr>
    <h2>
        <?php echo $integrante5; ?>
    </h2>

    <!-- ================== LIAM (integrante5) ================== -->
    <div class="gif">
        <video width="700" controls autoplay muted>
            <source src="..\videos\video.mp4\videoliam.mp4.png.mp4.mp4" type="video/mp4">
        </video>
    </div>

    <p>
        Frequentemente apontado como uma das vozes mais estáveis e pilares vocais do grupo, participando ativamente da composição de diversas faixas. Em sua fase solo, explorou o pop e o R&B comercial.
    </p>




    <a href="https://www.eonline.com/news/one_direction" target="_blank">
        Ler notícias
    </a> <br>
    <a href="albunssolos.php">
        ver albuns
    </a>
    <hr>

    <!-- Rodapé global -->
    <?php include __DIR__ . '/../includes/footer.php';
    ?>

</body>

</html>