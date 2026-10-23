<!-- login da area do admin: so entra quem e admin -->
<?php

// sessao pra guardar os dados do admin quando loga
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// functions.php traz login_user() e a conexao
require_once __DIR__ . '/../includes/functions.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

        // loga igual no resto do site (senha com hash)
    if (login_user($conexao, $email, $senha)) {

        // depois confere se a conta e admin
        $sql = "SELECT tipo FROM usuarios WHERE email = :email";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':email', $_SESSION['email']);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && $resultado['tipo'] === 'admin') {
            // admin confirmado → abre a area do admin
            header('Location: /projetointegrador/app/admin.php');
            exit();
        }

        // usuario comum nao entra na area do admin
        $mensagem = 'Esta conta não é de administrador.';

    } else {
        // email ou senha errados (mensagem generica)
        $mensagem = 'E-mail ou senha incorretos.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administration | One Direction</title>

    <style>
        /* tema escuro pra diferenciar a area do admin */
        body {
            background-color: #262626;
            color: #ffffff;
            font-family: Georgia, 'Times New Roman', Times, serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        main {
            background-color: #1f1f1f;
            border: 2px solid #A63437;
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 420px;
            box-sizing: border-box;
        }

        h1 {
            color: #B4C4D4;
            font-size: 1.3rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        label {
            display: block;
            color: #A08175;
            font-size: 0.9em;
            margin: 12px 0 4px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #A63437;
            border-radius: 6px;
            background-color: #262626;
            color: #ffffff;
            font-family: inherit;
        }

        /* Botão em largura total, hover escurece */
        input[type="submit"] {
            width: 100%;
            margin-top: 18px;
            padding: 12px;
            background-color: #A63437;
            color: #ffffff;
            font-family: inherit;
            font-weight: bold;
            font-size: 1rem;
            border: none;
            border-radius: 30px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #4F1B27;
        }

        .mensagem {
            background-color: #4F1B27;
            color: #ffffff;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            font-size: 0.9em;
            margin-top: 14px;
        }

        .voltar {
            display: block;
            text-align: center;
            margin-top: 14px;
            color: #B4C4D4;
            font-size: 0.85em;
        }
    </style>
</head>

<body>

    <main>
        <h1>Área do Administrador</h1>

        <?php if ($mensagem !== ''): ?>
            <p class="mensagem"><?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <!-- POST pra propria pagina -->
        <form action="" method="POST">

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <input type="submit" value="Entrar como administrador">

        </form>

        <!-- volta pro site sem precisar logar -->
        <a href="/projetointegrador/pages/noticias.php" class="voltar">&lsaquo; Voltar para o site</a>
    </main>

</body>

</html>
