
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/verificar_login.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_SESSION['id'];
    $nome = trim($_POST['name_sobrenome']);
    $email = trim($_POST['email']);

    // Verifica se o email já pertence a outra conta.
    $sql = "SELECT id FROM usuarios
            WHERE email = :email AND id <> :id";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':id' => $id
    ]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {

        echo "Esse email já está sendo usado por outro usuário.";

    } else {

        // Atualiza somente o usuário que está conectado.
        $sql = "UPDATE usuarios
                SET name_sobrenome = :nome, email = :email
                WHERE id = :id";

        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':id' => $id
        ]);

        // Atualiza o nome que está guardado na sessão.
        $_SESSION['usuario'] = $nome;

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