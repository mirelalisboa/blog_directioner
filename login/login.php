<!-- =====================================================
     login/login.php — CRIAÇÃO DE CONTA (cadastro)
     ATENÇÃO: apesar do nome, esta página CRIA CONTA.
     A página que faz LOGIN é a login/cadastro.php
     (os nomes estão trocados desde o início do projeto;
     manter assim para não quebrar os links existentes).
     O QUE FAZ: recebe nome + e-mail + senha via POST,
     valida, grava via cadastra_user() (functions.php),
     inicia a sessão e manda para a página de notícias.
     POR QUÊ: porta de entrada de novos usuários do blog.
     ===================================================== -->
<?php

// ------------------------------------------------------------------
// Sessão: precisa existir ANTES de qualquer HTML, porque guardamos
// os dados do usuário nela logo após o cadastro.
// ------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------------------------------
// functions.php traz:
//  - cadastra_user()  → INSERT com senha hash (password_hash)
//  - consulta_user()  → SELECT por e-mail (para reabrir a sessão)
//  - e a conexão $conexao (inclui database/connect.php)
// ------------------------------------------------------------------
require_once __DIR__ . '/../includes/functions.php';

$mensagem = '';

// ------------------------------------------------------------------
// Só processa o formulário quando ele é enviado (POST).
// No primeiro acesso (GET) a página só é desenhada.
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pega os campos do formulário (?? '' evita erro de
    // índice inexistente quando o campo não vem).
    $nome  = trim($_POST['name_sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validações simples: campos cheios + formato de e-mail.
    if ($nome === '' || $email === '' || $senha === '') {
        $mensagem = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'Digite um e-mail válido.';
    } else {

        // Tenta gravar o usuário (senhas nunca puros: função hash).
        $resultado = cadastra_user($conexao, $nome, $email, $senha);

        if ($resultado === true) {

            // Busca de volta o usuário recém-criado para pegar o id.
            $usuario = consulta_user($conexao, $email);

            if ($usuario) {

                // Cria os dados da sessão:
                //  - session_regenerate_id: troca o id da sessão
                //    (medida básica contra fixação de sessão);
                //  - as 3 chaves abaixo são o que os guardas de
                //    login e as páginas do perfil usam.
                session_regenerate_id(true);

                $_SESSION['id']      = $usuario['id'];
                $_SESSION['usuario'] = $usuario['name_sobrenome'];
                $_SESSION['email']   = $usuario['email'];

                // Logado → vai direto para a página de notícias.
                header('Location: ../pages/noticias.php');
                exit();
            } else {
                $mensagem = 'Cadastro realizado, mas não foi possível iniciar a sessão.';
            }
        } else {
            // O mais comum: e-mail já cadastrado (UNIQUE no banco).
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
        /* Paleta: laranja de ação + bordô de fundo dos blocos */
        :root {
            --cor-laranja-brilhante: #F26C23;
            --cor-bordo: #461519;
            --cor-cinza-claro: #CCC9BE;
            --cor-azul-petroleo: #1C3B4C;
            --cor-laranja-terracota: #E38249;
        }

        /* Reset básico: sem margens/paddings e fonte padrão */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Fundo da página + centralização em coluna */
        body {
            background-color: var(--cor-cinza-claro);
            color: var(--cor-bordo);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Vídeo de abertura da página */
        video {
            max-width: 900px;
            width: 100%;
            margin: 20px 0;
            border-radius: 8px;
            border: 3px solid var(--cor-azul-petroleo);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Linha divisória entre vídeo e formulário */
        hr {
            width: 90%;
            max-width: 900px;
            border: none;
            height: 2px;
            background-color: var(--cor-laranja-terracota);
            margin-bottom: 20px;
        }

        /* Cartão branco central com o formulário */
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

        /* Caixa vermelha de mensagem de erro */
        .mensagem-erro {
            background-color: var(--cor-bordo);
            color: #ffffff;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        /* Rótulos e campos do formulário */
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

        /* Destaque no campo ativo (foco) */
        form input[type="text"]:focus,
        form input[type="email"]:focus,
        form input[type="password"]:focus {
            border-color: var(--cor-laranja-brilhante);
        }

        /* Espaçamento entre cada campo */
        .campo-grupo {
            margin-bottom: 15px;
        }

        /* Botão principal de envio (laranja, largura total) */
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
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--cor-laranja-terracota);
        }

        /* Botão "Limpar" (transparente, contorno) */
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

        /* Link "Já tem conta? Faça login" */
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

    <!-- Vídeo de abertura (a pasta videos/ tem nomes estranhos,
         legados do projeto — não renomear; ver AGENTS.md) -->
    <!-- Menu global do site -->
    <?php include __DIR__ . '/../includes/header.php'; ?><br>
    <video controls autoplay muted>
        <source src="../videos/video.mp4/videoone.mp4.png.mp4.mp4" type="video/mp4">
    </video>



    <hr>

    <!-- Cartão do formulário de criar conta -->
    <main>

        <h2>Criar conta</h2>

        <!-- Mostra a mensagem de erro (se houver) -->
        <?php if ($mensagem !== ''): ?>
            <p class="mensagem-erro">
                <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <!-- Formulário: POST para a própria página (action="") -->
        <form action="" method="POST">

            <div class="campo-grupo">
                <label for="name_sobrenome">Digite seu nome completo:</label>
                <input
                    type="text"
                    name="name_sobrenome"
                    id="name_sobrenome"
                    required>
            </div>

            <div class="campo-grupo">
                <label for="email">Digite seu e-mail:</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required>
            </div>

            <div class="campo-grupo">
                <label for="senha">Digite sua senha:</label>
                <input
                    type="password"
                    name="senha"
                    id="senha"
                    required>
            </div>

            <!-- Envia os dados para o próprio arquivo (POST acima) -->
            <button type="submit" class="btn-submit">Criar conta</button>

            <!-- Limpa os campos preenchidos -->
            <button type="reset" class="btn-reset">Limpar</button>

            <!-- Direciona quem já tem conta para a página de login -->
            <a href="/projetointegrador/login/cadastro.php" class="link-login">Já tem conta? Faça login</a>
        </form>

    </main>

    <!-- Rodapé global -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>