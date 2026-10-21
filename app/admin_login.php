<!-- =====================================================
     app/admin_login.php — LOGIN PRÓPRIO DA ADMINISTRAÇÃO
     O QUE FAZ: página dedicada para o administrador entrar.
     POR QUÊ: usa as mesmas credenciais dos usuários, mas só
     libera quem tem tipo='admin' na tabela usuarios —
     usuário comum que fizer login aqui recebe mensagem de
     acesso negado e não vê nada da administração.
     ===================================================== -->
<?php

// Sessão: guarda os dados do admin quando autentica.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// functions.php: login_user() faz a autenticação
// (password_verify + dados na sessão) e traz $conexao.
require_once __DIR__ . '/../includes/functions.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // 1) Autentica igual ao site inteiro (senha com hash).
    if (login_user($conexao, $email, $senha)) {

        // 2) Confere se a conta é administradora
        //    (SELECT com WHERE pelo e-mail da sessão).
        $sql = "SELECT tipo FROM usuarios WHERE email = :email";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':email', $_SESSION['email']);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && $resultado['tipo'] === 'admin') {
            // Admin confirmado → área do administrador.
            header('Location: /projetointegrador/app/admin.php');
            exit();
        }

        // Usuário comum não entra na administração.
        $mensagem = 'Esta conta não é de administrador.';

    } else {
        // Credenciais erradas (mensagem genérica de propósito).
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
        /* Tema escuro + vinho para diferenciar a área restrita
         do restante do site (que é claro/rosado) */
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

        <!-- POST para a própria página -->
        <form action="" method="POST">

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <input type="submit" value="Entrar como administrador">

        </form>

        <!-- Fuga para o site público (sem precisar logar) -->
        <a href="/projetointegrador/pages/noticias.php" class="voltar">&lsaquo; Voltar para o site</a>
    </main>

</body>

</html>
