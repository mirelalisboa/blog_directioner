<?php require_once __DIR__ . '/../includes/functions.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastre-se</title>
</head>

<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <form action="" method="post">
            <label for="email">email: </label>
            <input type="text" name="email" id="email"><br>

            <label for="senha">senha: </label>
            <input type="password" name="senha" id="senha"><br>


            <input type="submit" value="Cadastrar">
            <input type="reset" value="Limpar">
        </form>

        <?php
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            cadastra_user($conexao, $_POST['nome'], $_POST['email'], $_POST['senha'],);
            header("location:". __DIR__ ."/../index.php");
            exit();
        }
        ?>
    </main>
    <?php include  __DIR__ .'/../includes/footer.php'; ?>
</body>
</html>