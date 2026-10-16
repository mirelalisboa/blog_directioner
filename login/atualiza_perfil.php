

<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verificauser.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pega o e-mail atual que está guardado na sessão.
    $email_atual = $_SESSION['email'];

    $nome = trim($_POST['name_sobrenome']);
    $email = trim($_POST['email']);

    // Verifica se o novo e-mail já pertence a outro usuário.
    $sql = "SELECT email FROM usuarios
            WHERE email = :email AND email <> :email_atual";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':email_atual' => $email_atual
    ]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {

        echo "Esse e-mail já está sendo usado por outro usuário.";

    } else {

        // Atualiza o nome e o e-mail usando o e-mail atual como referência.
        $sql = "UPDATE usuarios
                SET name_sobrenome = :nome, email = :email
                WHERE email = :email_atual";

        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':email_atual' => $email_atual
        ]);

        // Atualiza os dados guardados na sessão.
        $_SESSION['usuario'] = $nome;
        $_SESSION['email'] = $email;

        header("Location: perfil.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Atualizar Conta</title>

</head>

<body>

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>

        <h1>Atualizar minha conta</h1>

        <form method="post">

            <label for="name_sobrenome">
                Nome:
            </label>

            <input type="text"
                   name="name_sobrenome"
                   id="name_sobrenome"
                   required>

            <br><br>

            <label for="email">
                Email:
            </label>

            <input type="email"
                   name="email"
                   id="email"
                   required>

            <br><br>

            <label for="senha">
                Senha:
            </label>

            <input type="password"
                   name="senha"
                   id="senha"
                   required>

            <br><br>

            <input type="submit" value="Atualizar">

        </form>

        <br>

        <input type="button"
               value="Voltar para o perfil"
               onclick="window.location.href='perfil.php'">

    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>