
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    // Procura o usuário pelo email.
    $usuario = consulta_user($conexao, $email);

    // Confere a senha criptografada.
    if ($usuario && password_verify($senha, $usuario["senha"])) {

        session_regenerate_id(true);

        $_SESSION["id"] = $usuario["id"];
        $_SESSION["usuario"] = $usuario["name_sobrenome"];

        // Redireciona após o login.
        header("Location: ../pages/noticias.php");
        exit();

    } else {
        $erro = "Email ou senha incorretos!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h1>Faça login para continuar</h1>

        <?php
        if (isset($erro)) {
            echo "<p>" . htmlspecialchars($erro) . "</p>";
            echo '<a href="login.php">Tentar novamente</a>';
        }
        ?>

        <form action="" method="post">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <br>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
            <br>

            <input type="submit" value="Entrar">
            <input type="reset" value="Limpar">

        </form>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>