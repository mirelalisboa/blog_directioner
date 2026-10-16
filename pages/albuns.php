<?php

$album1 = "Up All Night";
$album2 = "Take Me Home";
$album3 = "Midnight Memories";
$album4 = "FOUR";
$album5 = "Made In The A.M.";


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

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <h1>Álbuns</h1>

    <hr>

    <h2>
        <?php echo $album1; ?>
    </h2>

    <p>
        Primeiro álbum de estúdio do One Direction, lançado em 2011.
        Foi o álbum que marcou o início da carreira musical do grupo e
        trouxe músicas que ficaram muito conhecidas, como What Makes You Beautiful
        e One Thing.
    </p>

    <input type="button" value="Ouvir no YouTube"
    onclick="window.location.href='https://www.youtube.com/results?search_query=One+Direction+Up+All+Night+album'">

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

    <hr>

    <?php include  __DIR__ . '/../includes/footer.php';
    include __DIR__ . '/../pages/contato.php';
    ?>

</body>

</html>