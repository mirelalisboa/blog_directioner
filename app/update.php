<?php require_once  __DIR__ .'/../includes/functions.php'; 
require_once __DIR__ . '/login/verificalogin.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>atualizar</title>
</head>

<body>
    <?php include __DIR__ .'/../includes/header.php'; ?>
    <main>
        <form action="" method="post">
            <label for="id">id </label>
            <input type="number" name="id" id="id" placeholder="insira o id para continuar" require><br>
            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome"><br>

            <label for="turma">Turma: </label>
            <input type="text" name="turma" id="turma"><br>

            <label for="email">E-mail: </label>
            <input type="email" name="email" id="email"><br>

            <label for="nasc">Nascimento: </label><br>
            <input type="date" name="nasc" id="nasc">

            <label for="">Ativo</label><br>
            <input type="radio" name="ativo" id="ativo" value="true">
            <label for="ativo">SIM. </label>
            <input type="radio" name="ativo" id="ativo" value="false">
            <label for="ativo">NÃO. </label><br>

            <input type="submit" value="atualizar">
            <input type="reset" value="Limpar">
        </form>

        <?php
        //($conexao, $id, $nome, $email)
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            atualizar_user($conexao, $_POST['id'], $_POST['nome'],$_POST['email']);
        }
        ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>