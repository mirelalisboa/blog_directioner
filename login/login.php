<!DOCTYPE html>
<html lang="pt-br">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Direction</title>
</head>


<body>
    <video width="900" controls autoplay muted>
        <source src="videos/video.mp4" type="video/mp4">
    </video>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <hr>
    <br>
    <br>
    <main>


            <form action="create.php" method="POST">
            


            <label for="name_sobrenome">Digite Seu Nome Completo </label><br>
            <input type="text" name="nome completo" id="name_sobrenome"><br>


            <label for="email">Digite Seu E-mail: </label><br>
            <input type="email" name="email" id="email"><br>


            <label for="senha">Digite Sua Senha: </label><br>
            <input type="password" name="senha" id="senha"><br>


            <input type="button" value="Criar Conta" onclick="window.location.href='/pages/noticias.php'"> <br>
            <input type="button" value="ja tem conta?" onclick="window.location.href='/../login/cadastro.php'"> <br>
            <input type="reset" value="Limpar">


        </form>
    </main>




    <?php include  __DIR__ . '/../includes/footer.php';
    ?>

</body>


</html