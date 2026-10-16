<?php

$epoca1 = "2010 - 2012";
$epoca2 = "2013 - 2015";
$epoca3 = "2016 - 2020";


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

    <video width="1000" controls autoplay muted>
        <source src="videos/video.mp4" type="video/mp4">
    </video>

    <h3>Linha do Tempo </h3>
    <hr>



    <input type="button" value="2010-11" onclick="window.location.href='/projetointegrador/images/2010-11'"> |

    <input type="button" value="2012-13" onclick="window.location.href='/projetointegrador/images/2012-13'"> |

    <input type="button" value="2014-15" onclick="window.location.href='/projetointegrador/images/2014-15'"> |

    <input type="button" value="2015-atual" onclick="window.location.href='/projetointegrador/images/2015-atual'">


</body>
<hr>
<?php include __DIR__ . '/../includes/footer.php';
include __DIR__ . '/../pages/contato.php';
?>

</html>