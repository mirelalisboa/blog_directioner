<!-- =====================================================
     login/atualiza_perfil.php — ATUALIZAR CONTA (nome/e-mail)
     O QUE FAZ: permite à pessoa logada trocar nome e e-mail.
     POR QUÊ: versão antiga dessa função; a página completa é
     app/update.php (que também troca senha e foto). Mantida
     como página de edição simples do projeto.
     ===================================================== -->
<?php

// Sessão: atualiza os dados guardados após o UPDATE.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Guarda de login + funções + $conexao.
require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verificauser.php';

// Só faz algo quando o formulário é enviado (POST).
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pega o e-mail atual da sessão: é ele que localiza a conta
    // no banco (a referência antes da troca).
    $email_atual = $_SESSION['email'];

    $nome  = trim($_POST['name_sobrenome']);
    $email = trim($_POST['email']);

    // Verifica se o novo e-mail já pertence a outro usuário.
    // ("AND email <> :email_atual" ignora a própria conta.)
    $sql = "SELECT email FROM usuarios
            WHERE email = :email AND email <> :email_atual";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        ':email'       => $email,
        ':email_atual' => $email_atual
    ]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {

        echo "Esse e-mail já está sendo usado por outro usuário.";

    } else {

        // UPDATE usando o e-mail ATUAL como chave de localização.
        $sql = "UPDATE usuarios
                SET name_sobrenome = :nome, email = :email
                WHERE email = :email_atual";

        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':nome'        => $nome,
            ':email'       => $email,
            ':email_atual' => $email_atual
        ]);

        // Atualiza os dados guardados na sessão: montante importante,
        // porque páginas do perfil pesquisam por $_SESSION['email'].
        $_SESSION['usuario'] = $nome;
        $_SESSION['email']   = $email;

        header("Location: /projetointegrador/app/perfil.php");
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

    <!-- Menu global -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>

        <h1>Atualizar minha conta</h1>

        <!-- Formulário POST para a própria página -->
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

            <!-- Campo decorativo: este formulário não troca senha
                 (a troca real de senha fica em app/update.php) -->
            <input type="password"
                   name="senha"
                   id="senha"
                   required>

            <br><br>

            <input type="submit" value="Atualizar">

        </form>

        <br>

        <!-- Volta para o perfil (área autenticada, em app/) -->
        <input type="button"
               value="Voltar para o perfil"
               onclick="window.location.href='/projetointegrador/app/perfil.php'">

    </main>

    <!-- Rodapé global -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>
