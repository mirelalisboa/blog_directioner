<?php

// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Importa as funções do projeto
require_once __DIR__ . '/../includes/functions.php';

$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe os dados do formulário
    $nome = trim($_POST['name_sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Verifica se todos os campos foram preenchidos
    if ($nome === '' || $email === '' || $senha === '') {
        $mensagem = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'Digite um e-mail válido.';
    } else {

        // Cadastra o usuário usando a função existente
        $resultado = cadastra_user(
            $conexao,
            $nome,
            $email,
            $senha
        );

        // Verifica se o cadastro deu certo
        if ($resultado === true) {

            // Busca os dados do usuário cadastrado
            $usuario = consulta_user($conexao, $email);

            if ($usuario) {

                // Cria uma nova sessão de login
                session_regenerate_id(true);

                $_SESSION['id'] = $usuario['id'];
                $_SESSION['usuario'] = $usuario['name_sobrenome'];

                // Entra na página de notícias
                header('Location: ../pages/noticias.php');
                exit();

            } else {
                $mensagem = 'Cadastro realizado, mas não foi possível iniciar a sessão.';
            }

        } else {
            $mensagem = 'Não foi possível cadastrar. Verifique se o e-mail já está cadastrado.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Direction</title>
    <style>
        :root {
            --cor-laranja-brilhante: #F26C23;
            --cor-bordo: #461519;
            --cor-cinza-claro: #CCC9BE;
            --cor-azul-petroleo: #1C3B4C;
            --cor-laranja-terracota: #E38249;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: var(--cor-cinza-claro);
            color: var(--cor-bordo);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        video {
            max-width: 900px;
            width: 100%;
            margin: 20px 0;
            border-radius: 8px;
            border: 3px solid var(--cor-azul-petroleo);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        hr {
            width: 90%;
            max-width: 900px;
            border: none;
            height: 2px;
            background-color: var(--cor-laranja-terracota);
            margin-bottom: 20px;
        }

        main {
            background-color: #ffffff;
            border: 2px solid var(--cor-laranja-terracota);
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
        }

        h2 {
            color: var(--cor-bordo);
            text-align: center;
            margin-bottom: 20px;
        }

        .mensagem-erro {
            background-color: var(--cor-bordo);
            color: #ffffff;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        form label {
            display: block;
            color: var(--cor-azul-petroleo);
            font-weight: bold;
            margin-bottom: 6px;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 2px solid var(--cor-cinza-claro);
            border-radius: 6px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        form input[type="text"]:focus,
        form input[type="email"]:focus,
        form input[type="password"]:focus {
            border-color: var(--cor-laranja-brilhante);
        }

        .campo-grupo {
            margin-bottom: 15px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: var(--cor-laranja-brilhante);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--cor-laranja-terracota);
        }

        .btn-reset {
            width: 100%;
            padding: 10px;
            background-color: transparent;
            color: var(--cor-bordo);
            border: 2px solid var(--cor-bordo);
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-reset:hover {
            background-color: var(--cor-bordo);
            color: #ffffff;
        }

        .link-login {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: var(--cor-azul-petroleo);
            text-decoration: none;
            font-weight: bold;
        }

        .link-login:hover {
            color: var(--cor-laranja-brilhante);
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <video controls autoplay muted>
        <source src="videos/video.mp4" type="video/mp4">
    </video>
    <?php include __DIR__ . '/../includes/header.php'; ?><br>


    <hr>

    <main>

        <h2>Criar conta</h2>

        <!-- Exibe mensagens de erro -->
        <?php if ($mensagem !== ''): ?>
            <p class="mensagem-erro">
                <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <!-- Formulário de cadastro -->
        <form action="" method="POST">

            <div class="campo-grupo">
                <label for="name_sobrenome">Digite seu nome completo:</label>
                <input
                    type="text"
                    name="name_sobrenome"
                    id="name_sobrenome"
                    required
                >
            </div>

            <div class="campo-grupo">
                <label for="email">Digite seu e-mail:</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                >
            </div>

            <div class="campo-grupo">
                <label for="senha">Digite sua senha:</label>
                <input
                    type="password"
                    name="senha"
                    id="senha"
                    required
                >
            </div>

            <!-- Envia os dados para o PHP -->
            <button type="submit" class="btn-submit">Criar conta</button>

            
            <button type="reset" class="btn-reset">Limpar</button>
            
            <!-- Direciona quem já possui uma conta -->
            <a href="cadastro.php" class="link-login">Já tem conta? Faça login</a>
        </form>

    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>