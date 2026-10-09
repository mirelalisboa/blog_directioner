<?php

$integrante1 = "Harry Styles";
$integrante2 = "Louis Tomlinson";
$integrante3 = "Niall Horan";
$integrante4 = "Zayn Malik";
$integrante5 = "Liam Payne";



require_once __DIR__ . '/../includes/verificalogin.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> One Direction</title>

</head>

<body>

    <video width="1000" controls autoplay muted>
        <source src="videos/video.mp4" type="video/mp4">
    </video>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <h1>Integrantes do One Direction</h1>

    <hr>

    <div class="gif">
        <video width="1000" controls autoplay muted>
            <source src="videos\video.mp4\harry.mp4.gif" type="video/mp4">
        </video>
    </div>


    <h2>
        <?php echo $integrante1; ?>
    </h2>


    <h3>Notícias</h3>

    <a href="https://www.rte.ie/entertainment/2026/0705/1581860-harry-styles-pays-tribute-to-one-direction-bandmates/" target="_blank">
        Ler notícia
    </a>
    <h2>
        <?php echo $integrante2; ?>
    </h2>
    <div class="gif">
        <video width="1000" controls autoplay muted>
            <source src="videos\video.mp4\harry.mp4.gif" type="video/mp4">
        </video>
    </div>

    <hr>

    <img src="../imagens/louis.jpg" alt="Louis Tomlinson" width="300">



    <h3>Notícias</h3>

    <a href="https://louis-tomlinson.com/" target="_blank">
        Ver novidades
    </a>


    <hr>
    <h2>
        <?php echo $integrante3; ?>
    </h2>

    <div class="gif">
        <video width="1000" controls autoplay muted>
            <source src="videos\video.mp4\harry.mp4\PinGrab_1791544963829.gif" type="video/mp4">
        </video>
    </div>



    <h3>Notícias</h3>

    <a href="https://portalpopline.com.br/membros-one-direction-albuns-2026-desempenho/" target="_blank">
        Ler notícia
    </a>


    <hr>



    <h2>
        <?php echo $integrante4; ?>
    </h2>
    <div class="gif">
        <video width="1000" controls autoplay muted>
            <source src="videos\video.mp4\harry.mp4\PinGrab_1791544963829.gif" type="video/mp4">
        </video>
    </div>

    <h3>Notícias</h3>

    <a href="https://www.inzayn.com/tour/#/" target="_blank">
        Ler notícias
    </a>


    <hr>
    <h2>
        <?php echo $integrante5; ?>
    </h2>

    <div class="gif">
        <video width="1000" controls autoplay muted>
            <source src="videos\video.mp4\harry.mp4\PinGrab_1791544963829.gif" type="video/mp4">
        </video>
    </div>



    <h3>Notícias</h3>


    <a href="https://www.eonline.com/news/one_direction" target="_blank">
        Ler notícias
    </a>

    <hr>
    <?php include __DIR__ . '/../includes/footer.php';
    ?>

</body>

</html>