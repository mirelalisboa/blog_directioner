
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verificauser.php';

// Verifica se o formulário foi enviado.
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Verifica o email e a senha da conta existente.
    if (login_user($conexao, $email, $senha)) {

        // Se estiver correto, vai para a página de notícias.
        header("Location: ../pages/noticias.php");
        exit();

    } else {

        // Mensagem se os dados estiverem incorretos.
        $erro = "Email ou senha incorretos!";
    }
}

?>

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

        <h1>Entre na sua conta</h1>

        <?php
        if (isset($erro)) {
            echo "<p>" . htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') . "</p>";
            echo '<a href="cadastro.php">Tentar novamente</a>';
        }
        ?>

        <form action="" method="post">

            <label for="email">Email:</label>
            <input
                type="email"
                name="email"
                id="email"
                required
            >
            <br>

            <label for="senha">Senha:</label>
            <input
                type="password"
                name="senha"
                id="senha"
                required
            >
            <br>

            
            <input type="button" value="Entrar" onclick="window.location.href='/../pages/noticias.php'"> <br>

            <input type="reset" value="Limpar">br

             <input type="button" value="voltar" onclick="window.location.href='/../login/login.php'">

        </form>

    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>

