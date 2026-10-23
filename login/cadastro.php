<!-- entrar na conta (nome trocado: cadastro.php faz o login
     e login.php cria a conta; mantive igual) -->
<?php

// a sessao guarda os dados do usuario depois do login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// functions.php traz login_user() e a conexao
require_once __DIR__ . '/../includes/functions.php';

// Só busca no banco quando o formulário é enviado (POST).
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // login_user(): acha o usuario pelo email, confere a senha
    // (contra o hash) e grava id/usuario/email na sessao
    if (login_user($conexao, $email, $senha)) {

        // Logado com sucesso → vai para as notícias.
        header("Location: ../pages/noticias.php");
        exit();
    } else {

        // email ou senha errados (mensagem generica, pra nao
        // revelar qual dos dois esta errado)
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

    <style>
        /* Paleta: vermelho de destaque, verde militar dos botões */
        :root {
            --cor-vermelho: #A62B32;
            --cor-azul-cinza: #AAB6C9;
            --cor-rosa-palido: #E6C8C4;
            --cor-verde-militar: #5B703A;
            --cor-terracota: #C48B74;
        }

        /* Fundo rosa pálido + fonte limpa */
        body {
            background-color: var(--cor-rosa-palido);
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Cartão branco central com o formulário */
        main {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-top: 5px solid var(--cor-vermelho);
        }

        h1 {
            color: var(--cor-vermelho);
            text-align: center;
        }

        /* Rótulos em negrito */
        label {
            display: block;
            margin-top: 10px;
            color: #333;
            font-weight: bold;
        }

        /* Campos de e-mail e senha */
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid var(--cor-azul-cinza);
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Borda verde quando o campo está em foco */
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--cor-verde-militar);
        }

        /* Botões principais (verde militar) */
        input[type="button"],
        input[type="submit"] {
            background-color: var(--cor-verde-militar);
            color: white;
            border: none;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }

        input[type="button"]:hover,
        input[type="submit"]:hover {
            opacity: 0.9;
        }

        /* Botão Limpar (terracota) */
        input[type="reset"] {
            background-color: var(--cor-terracota);
            color: white;
            border: none;
            padding: 8px 12px;
            margin-top: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        /* Botão "Voltar" (azul cinza) */
        .btn-voltar {
            background-color: var(--cor-azul-cinza) !important;
            color: #333 !important;
        }

        /* Caixa vermelha das mensagens de erro */
        .mensagem-erro {
            color: var(--cor-vermelho);
            background-color: #fce8e6;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid var(--cor-vermelho);
            text-align: center;
        }
    </style>
</head>

<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Federo&display=swap');

        /* paleta de cores */
        :root {
            /* Paleta 1: Azul e Branco */
            --p1-blue: #1769AA;
            --p1-light-blue: #87CEEB;
            --p1-white: #FFFFFF;

            /* Paleta 2: Preto e Branco */
            --p2-black: #111111;
            --p2-dark-gray: #555555;
            --p2-white: #FFFFFF;

            /* Paleta 3: Vermelho e Preto */
            --p3-dark-red: #C62828;
            --p3-red: #E53935;
            --p3-black: #171717;

            /* Paleta 4: Azul-marinho, Cinza e Branco */
            --p4-navy: #142B4A;
            --p4-slate-gray: #708090;
            --p4-ice-white: #E8EDF2;

            /* variaveis do tema */
            --fundo: var(--p4-navy);
            /* Fundo principal */
            --card: #1C365D;
            /* Fundo dos cartões/caixas */
            --azul: var(--p4-slate-gray);
            /* Bordas e detalhes */
            --azul-claro: #A9C4CE;
            /* Destaques e foco */
            --marrom: #2B4566;
            /* Botões de ação */
            --marrom-claro: #385B87;
            /* Hover dos botões */
            --cinza: var(--p4-slate-gray);
            /* Bordas e linhas */
            --texto: var(--p4-ice-white);
            /* Texto principal */
            --texto-suave: #B0C0D0;
            /* Rótulos e textos secundários */
            --erro-fundo: #4A1C1D;
            /* Fundo da mensagem de erro */
            --erro-texto: #FFB3B3;
            /* Texto do erro */
        }

        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Visual geral da página */
        body {
            background-color: var(--fundo);
            color: var(--texto);
            font-family: Georgia, 'Times New Roman', Times, serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 10px 15px;
        }

        /* Vídeo grande e centralizado */
        video {
            display: block;
            width: 100%;
            max-width: 900px;
            height: auto;
            margin: 22px auto;
            border: 2px solid var(--azul);
            border-radius: 12px;
            background-color: var(--fundo);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.45);
        }

        /* Cartão central do formulário */
        main {
            width: 100%;
            max-width: 420px;
            margin: 20px auto 40px;
            padding: 25px 20px;
            background-color: var(--card);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--azul);
            border-top: 5px solid var(--azul-claro);
        }

        h1 {
            color: var(--texto);
            text-align: center;
            font-size: 1.6em;
            margin-bottom: 20px;
        }

        /* Rótulos */
        label {
            display: block;
            margin-top: 12px;
            color: var(--texto-suave);
            font-weight: bold;
            font-size: 0.9em;
        }

        /* Campos de input (email e senha) */
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border: 1px solid var(--azul);
            border-radius: 6px;
            background-color: #0E1E34;
            color: var(--texto);
            font-family: inherit;
            font-size: 0.9em;
            outline: none;
            transition: border-color 0.2s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--azul-claro);
        }

        /* Botão Entrar (Submit) */
        input[type="submit"] {
            background-color: var(--marrom);
            color: var(--texto);
            border: 1px solid var(--azul);
            padding: 10px 15px;
            margin-top: 20px;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            font-family: inherit;
            font-size: 0.9em;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        input[type="submit"]:hover {
            background-color: var(--marrom-claro);
            transform: translateY(-1px);
        }

        /* Botão Limpar (Reset) */
        input[type="reset"] {
            background-color: #24354A;
            color: var(--texto-suave);
            border: 1px solid var(--azul);
            padding: 8px 12px;
            margin-top: 10px;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
            font-family: inherit;
            font-size: 0.85em;
            transition: background-color 0.2s ease;
        }

        input[type="reset"]:hover {
            background-color: #314763;
            color: var(--texto);
        }

        /* Botão Voltar */
        .btn-voltar {
            background-color: transparent !important;
            color: var(--azul-claro) !important;
            border: 1px solid var(--azul) !important;
            padding: 8px 12px;
            margin-top: 10px;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
            font-family: inherit;
            font-size: 0.85em;
            font-weight: bold;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .btn-voltar:hover {
            background-color: var(--azul) !important;
            color: var(--texto) !important;
        }

        /* Mensagem de Erro */
        .mensagem-erro {
            color: var(--erro-texto);
            background-color: var(--erro-fundo);
            padding: 10px;
            border-radius: 6px;
            border: 1px solid var(--erro-texto);
            text-align: center;
            font-size: 0.88em;
            margin-bottom: 15px;
        }

        /* Links adicionais no formulário (Admin e Tentar Novamente) */
        main a {
            display: block;
            text-align: center;
            color: var(--azul-claro);
            text-decoration: none;
            font-size: 0.8em;
            margin-top: 12px;
            transition: color 0.2s ease;
        }

        main a:hover {
            color: var(--texto);
            text-decoration: underline;
        }

        footer {
            text-align: center;
            width: 100%;
            margin-top: auto;
        }

        @media (max-width: 600px) {
            video {
                margin: 16px auto;
            }

            main {
                margin: 10px auto 30px;
                padding: 20px 15px;
            }
        }
    </style>
    <!-- menu do site (sem login ainda, entao so o inicio aparece) -->
    
    <video controls autoplay muted width="1000">
        <source src="..\videos\video.mp4\videoperfect.mp4.png.mp4.mp4" type="video/mp4">
    </video>
    <main>

        <h1>Entre na sua conta</h1>

        <?php
        // Mostra o erro e um jeito de tentar de novo.
        if (isset($erro)) {
            echo "<p class='mensagem-erro'>" . htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') . "</p>";
            echo '<a href="./cadastro.php">Tentar novamente</a>';
        }
        ?>

        <!-- Formulário de login: POST para a própria página -->
        <form action="" method="post">

            <label for="email">Email:</label>
            <input
                type="email"
                name="email"
                id="email"
                required>

            <label for="senha">Senha:</label>
            <input
                type="password"
                name="senha"
                id="senha"
                required>

            <input type="submit" value="Entrar">

            <input type="reset" value="Limpar">

            <!-- Volta para a página de criar conta -->
            <input type="button" class="btn-voltar" value="Voltar" onclick="window.location.href='./login.php'">

            <br><br>

            <!-- link pra area do admin (so entra conta tipo 'admin') -->
            <a href="/projetointegrador/app/admin_login.php" style="font-size: 0.75em; color: #333;">Área do Administrador</a>

        </form>

    </main>

    <!-- Rodapé global -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>