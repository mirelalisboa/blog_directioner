<!-- linha do tempo: botoes por era que abrem a galeria de
     fotos dela (as fotos ficam na pasta images/<era>/) -->
<?php

// Rótulos dos períodos em variáveis (padrão do projeto).
$epoca1 = "2010 - 2012";
$epoca2 = "2013 - 2015";
$epoca3 = "2016 - 2020";

// fotos por era: url do arquivo, link da fonte no
// wikimedia commons e a legenda de cada foto
$fotos_eras = array(
    // Era X Factor Live (2010-11): os primeiros shows da banda.
    "2010-11" => array(
        array(
            "url"     => "../images/2010-11/1.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_X_Factor_Live_Glasgow.jpg",
            "legenda" => "One Direction no X Factor Live, SECC, Glasgow (abril de 2011).",
        ),
        array(
            "url"     => "../images/2010-11/2.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_X_Factor_Live_Glasgow_2_(cropped).jpg",
            "legenda" => "A banda no palco do X Factor Live, SECC, Glasgow (2011).",
        ),
        array(
            "url"     => "../images/2010-11/3.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_X_Factor_Live_Glasgow_3.jpg",
            "legenda" => "X Factor Live, SECC, Glasgow (1º de abril de 2011).",
        ),
        array(
            "url"     => "../images/2010-11/4.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:Harry_Styles,_Liam_Payne_and_Louis_Tomlinson_X_Factor_Live_Glasgow.jpg",
            "legenda" => "Harry, Liam e Louis no X Factor Live, Glasgow (2011).",
        ),
    ),
    // Era Take Me Home (2012-13): shows pelo mundo todo.
    "2012-13" => array(
        array(
            "url"     => "../images/2012-13/1.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_2012_Stockholm.jpg",
            "legenda" => "A banda no Mosebacke torg, Estocolmo (maio de 2012).",
        ),
        array(
            "url"     => "../images/2012-13/2.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_at_the_Logies_Awards_2012.jpg",
            "legenda" => "One Direction no tapete vermelho do TV Week Logie Awards, Austrália (2012).",
        ),
        array(
            "url"     => "../images/2012-13/3.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_Sydney_2.jpg",
            "legenda" => "Show no Hordern Pavilion, Sydney, Austrália (2012).",
        ),
        array(
            "url"     => "../images/2012-13/4.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_NRJ_Music_Awards_2013.jpg",
            "legenda" => "One Direction no NRJ Music Awards, Cannes (janeiro de 2013).",
        ),
        array(
            "url"     => "../images/2012-13/5.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_(1D)_figures_at_Madame_Tussauds_(12329579643).jpg",
            "legenda" => "As figuras de cera da banda no Madame Tussauds, Londres (maio de 2013).",
        ),
        array(
            "url"     => "../images/2012-13/6.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_West_Valley_City_25-07-2013_IMGP0058_(9369899476).jpg",
            "legenda" => "Take Me Home Tour no Maverik Center, Utah (julho de 2013).",
        ),
    ),
    // Era Four / Made in the A.M. (2014-15): últimos álbuns e turnê.
    "2014-15" => array(
        array(
            "url"     => "../images/2014-15/1.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:Harry_Styles,_Liam_Payne_%26_Niall_Horan_(14114429598)_(cropped).jpg",
            "legenda" => "Harry, Liam e Niall em 2014.",
        ),
        array(
            "url"     => "../images/2014-15/2.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_ARIA_Awards_2014.jpg",
            "legenda" => "One Direction no ARIA Awards, Austrália (2014).",
        ),
        array(
            "url"     => "../images/2014-15/3.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:OnedirectionWWATchile_(cropped).jpg",
            "legenda" => "Where We Are Tour no Estádio Nacional, Chile (abril de 2014).",
        ),
        array(
            "url"     => "../images/2014-15/4.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:One_Direction_at_214._Wetten,_dass.._show_in_Graz,_8._Nov._2014_02.jpg",
            "legenda" => "A banda no programa Wetten, dass..?, Graz, Áustria (novembro de 2014).",
        ),
        array(
            "url"     => "../images/2014-15/5.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:2015_-_One_Direction_%22On_The_Road_Again%22_(Sheffield)_One_Direction_(22597177776).jpg",
            "legenda" => "On the Road Again Tour em Sheffield (outubro de 2015).",
        ),
        array(
            "url"     => "../images/2014-15/6.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:2015_-_One_Direction_%22On_The_Road_Again%22_(Sheffield)_One_Direction_(22623217085).jpg",
            "legenda" => "On the Road Again Tour em Sheffield (outubro de 2015).",
        ),
    ),
    // Era do hiato / atual (2016-atual): carreiras solo dos integrantes.
    "2016-atual" => array(
        array(
            "url"     => "../images/2016-atual/1.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:Harry_Styles_Tour_Opening.jpg",
            "legenda" => "Harry Styles na abertura de sua turnê solo (2017).",
        ),
        array(
            "url"     => "../images/2016-atual/2.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:Niall_Flicker_Sessions_Orlando_2017_Since_We%27re_Alone.jpg",
            "legenda" => "Niall Horan no Flicker Sessions, Orlando (2017).",
        ),
        array(
            "url"     => "../images/2016-atual/3.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:Niall_Horan_Flicker_Sessions_Ireland_2017.png",
            "legenda" => "Niall Horan no Flicker Sessions, Irlanda (2017).",
        ),
        array(
            "url"     => "../images/2016-atual/4.jpg",
            "origem"  => "https://commons.wikimedia.org/wiki/File:Lt_hard_rock_2023_1.jpg",
            "legenda" => "Louis Tomlinson em apresentação (2023).",
        ),
    ),
);

// titulo exibido na galeria de cada era (mesma chave das fotos)
$titulos_eras = array(
    "2010-11"    => "Era X Factor Live (2010-2011)",
    "2012-13"    => "Era Take Me Home (2012-2013)",
    "2014-15"    => "Era Four / Made in the A.M. (2014-2015)",
    "2016-atual" => "Hiato — carreiras solo (2016-atual)",
);

// a era escolhida vem da url (?ano=...); se o valor nao
// for uma era cadastrada, so o menu de eras aparece
$ano_era = "";
if (isset($_GET["ano"]) && array_key_exists((string) $_GET["ano"], $fotos_eras)) {
    $ano_era = (string) $_GET["ano"];
}

// exige login
require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../login/verificauser.php';

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Direction</title>
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

        /* Títulos dos álbuns e seções */
        h2,
        h3 {
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

        /* Botões para ouvir no YouTube e eras */
        input[type="button"] {
            display: inline-block;
            margin: 6px 4px;
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

        /* Força o container dos botões a centralizar todo o conteúdo */
        .botoes-era {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px auto !important;
            text-align: center !important;
            color: var(--azul);
            /* Cor dos separadores '|' */
        }

        /* Sobrescreve o display: block anterior dos botões */
        .botoes-era input[type="button"] {
            display: inline-block !important;
            margin: 0 !important;
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

            h2,
            h3 {
                font-size: 1.15em;
            }

            body>p {
                font-size: 0.86em;
            }
        }

        /* Container dos botões de eras/anos */
        .eras,
        .menu-anos,
        .navegacao-anos {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px auto;
        }

        /* Esconde as barras verticais soltas (|) se existirem no HTML */
        .eras span,
        .menu-anos span {
            display: none;
        }

        /* Botões dos anos */
        .eras a,
        .menu-anos a,
        .navegacao-anos a {
            display: inline-block;
            padding: 6px 18px;
            border: 1px solid var(--azul);
            border-radius: 20px;
            background-color: var(--card);
            color: var(--texto);
            text-decoration: none;
            font-size: 0.88em;
            transition: all 0.2s ease;
        }

        /* Efeito ao passar o mouse */
        .eras a:hover,
        .menu-anos a:hover,
        .navegacao-anos a:hover {
            background-color: var(--azul);
            color: var(--texto);
            border-color: var(--azul-claro);
        }

        /* Botão do ano ativo/selecionado */
        .eras a.ativo,
        .menu-anos a.ativo,
        .navegacao-anos a.ativo {
            background-color: var(--marrom);
            border-color: var(--marrom-claro);
            color: var(--texto);
            font-weight: bold;
        }

        /* Subtítulo da era selecionada (ancora da galeria) */
        #fotos-era {
            color: var(--azul-claro);
            text-align: center;
            font-size: 1.15em;
            margin: 18px auto 6px;
        }

        /* Galeria de fotos da era (arquivos de images/<era>/) */
        .galeria-era {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 14px;
            max-width: 1080px;
            margin: 14px auto 24px;
        }

        .galeria-era figure {
            margin: 0;
            width: 250px;
            max-width: 100%;
            border: 1px solid var(--azul);
            border-radius: 10px;
            background-color: var(--card);
            overflow: hidden;
        }

        .galeria-era img {
            display: block;
            width: 100%;
            height: 210px;
            object-fit: cover;
            background-color: var(--fundo);
        }

        .galeria-era figcaption {
            padding: 8px 10px;
            color: var(--texto-suave);
            font-size: 0.78em;
            line-height: 1.45;
            text-align: center;
        }

        /* Link para a página de origem da foto */
        .galeria-era a {
            color: var(--azul-claro);
            text-decoration: none;
        }

        .galeria-era a:hover {
            text-decoration: underline;
        }
    </style>

    <!-- video de abertura -->
    <!-- Menu global -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <video controls autoplay muted>
        <source src="..\videos\video.mp4\videogotta.mp4.png.mp4.mp4" type="video/mp4">
    </video>
    <h3>Linha do Tempo </h3>
    <hr>

  <div class="eras">
    <?php foreach ($fotos_eras as $chave_era => $fotos_da_era): ?>
        <a href="?ano=<?php echo rawurlencode($chave_era); ?>#fotos-era"
           class="<?php echo $ano_era === $chave_era ? 'ativo' : ''; ?>">
           <?php echo htmlspecialchars($chave_era, ENT_QUOTES, 'UTF-8'); ?></a>
    <?php endforeach; ?>
</div>

<?php if ($ano_era !== ""): ?>
    <!-- galeria da era escolhida -->
    <h3 id="fotos-era">
        Fotos da era: <?php echo htmlspecialchars($titulos_eras[$ano_era], ENT_QUOTES, 'UTF-8'); ?>
    </h3>
    <div class="galeria-era">
        <?php foreach ($fotos_eras[$ano_era] as $foto): ?>
            <figure>
                <a href="<?php echo htmlspecialchars($foto["origem"], ENT_QUOTES, 'UTF-8'); ?>"
                   target="_blank" rel="noopener" title="Ver foto na página de origem">
                    <img src="<?php echo htmlspecialchars($foto["url"], ENT_QUOTES, 'UTF-8'); ?>"
                         alt="<?php echo htmlspecialchars($foto["legenda"], ENT_QUOTES, 'UTF-8'); ?>"
                         loading="lazy">
                </a>
                <figcaption>
                    <?php echo htmlspecialchars($foto["legenda"], ENT_QUOTES, 'UTF-8'); ?><br>
                    <a href="<?php echo htmlspecialchars($foto["origem"], ENT_QUOTES, 'UTF-8'); ?>"
                       target="_blank" rel="noopener">Fonte: Wikimedia Commons</a>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- sem era escolhida ainda: pede pra clicar acima -->
    <p class="mensagem">Clique em um período acima para ver as fotos da era.</p>
<?php endif; ?>

<hr>
</body>

<!-- Rodapé global -->
<?php include __DIR__ . '/../includes/footer.php'; ?>