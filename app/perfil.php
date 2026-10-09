<?php

require_once __DIR__ . '/../includes/verificar_login.php';
require_once __DIR__ . '/../includes/functions.php';

// Busca somente o perfil da conta conectada.
$usuario = consultar_perfil($conexao, $_SESSION['id']);

if (!$usuario) {
    echo "Não foi possível encontrar seu perfil.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meu Perfil</title>

</head>

<body>

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>

        <h1>Meu Perfil</h1>

        <p>
            <strong>Nome:</strong>
            <?php echo $usuario['nome']; ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo $usuario['email']; ?>
        </p>

        <br>

        <input type="button"
               value="Atualizar conta"
               onclick="window.location.href='atualizar_user.php'">

        <br><br>

        <input type="button"
               value="Excluir conta"
               onclick="window.location.href='delete.php'">

    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>