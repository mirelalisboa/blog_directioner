
<?php
require_once __DIR__ . '/../includes/verificalogin.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Direction</title>
</head>

<body>

    <video width="1000" controls autoplay muted>
          <source src="videos/video.mp4" type="video/mp4">
      </video>
 <?php include __DIR__ . '/../includes/header.php'; ?>
<hr><br>
<h2>Leia As Notícias Abaixo</h2>
<?php
        $link1 = "https://g1.globo.com/tudo-sobre/one-direction/";
        $link2 = "https://www.cnnbrasil.com.br/pop/musica/one-direction-banda-completa-16-anos-nesta-quinta-feira-23/";
        $link3 = "https://g1.globo.com/musica/noticia/2016/04/zayn-malik-disco-solo-tem-gritos-de-liberdade-indiretas-e-rb-moderninho.html";
        $link4 = "https://g1.globo.com/musica/noticia/2015/09/one-direction-lancara-primeiro-album-sem-zayn-malik-em-novembro.html";
    ?>

    

    <p>
        Clique abaixo para ler as notícias.
    </p>

    <a href="<?php echo $link1; ?>" target="_blank">
        Ler notícia
    </a>
<hr> <br>
    <a href="<?php echo $link2; ?>" target="_blank">
        Ler notícia
    </a>
<hr><br>


    <a href="<?php echo $link3; ?>" target="_blank">
        Ler notícia
    </a>
<hr><br>

<a href="<?php echo $link4; ?>" target="_blank">
        Ler notícia
</a>
<hr><br>
</body>
<hr>
 <?php include __DIR__ . '/../includes/footer.php';
       include __DIR__ . '/../pages/contato.php';
 ?>

</html>