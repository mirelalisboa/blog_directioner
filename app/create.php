<?php require_once  __DIR__ .'/../includes/functions.php';

require_once __DIR__ . '/login/verificalogin.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>apaga usuario</title>
</head>

<body>
    <?php include __DIR__ .  '/../includes/header.php'; ?>
    <h1>Apaga usuário</h1>
    <main></main>
    <form action="" method="post">
        <label for="email">Email: </label>
        <input type="text" name="email"  placeholder="insira o email para apagar" required><br>

        <label for="email">Senha: </label>
        <input type="password" name="senha"  placeholder="insira a senha para apagar" required><br>
        <input type="submit" value="Apagar">


    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        excluir_user($conexao, $_POST['id']);
    }
    ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>