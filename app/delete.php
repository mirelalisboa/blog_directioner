<!-- excluir a propria conta: pede confirmacao por post -->
<?php

// sessao ativa antes de qualquer html
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// exigencias de login (dois arquivos que checam a sessao)
require_once __DIR__ . '/../includes/verificalogin.php';
// functions.php: excluir_user() + $conexao.
require_once __DIR__ . '/../includes/functions.php';
// reforco da checagem de login
require_once __DIR__ . '/../login/verificauser.php';

// Verifica se existe um e-mail na sessão.
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: /projetointegrador/login/cadastro.php");
    exit();
}

$mensagem = "";

// so exclui quando o form e enviado (post):
// so abrir a pagina (get) nao apaga nada
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Guarda o e-mail da conta conectada.
    $email = $_SESSION['email'];

        // apaga a conta do banco pelo email
    if (excluir_user($conexao, $email)) {

        // encerra a sessao (a conta nao existe mais)
        $_SESSION = [];
        session_destroy();

        // volta pra pagina inicial
        header("Location: /projetointegrador/index.php");
        exit();

    } else {
        $mensagem = "Não foi possível excluir sua conta.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir conta</title>
</head>

<body>

    <!-- Menu global -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>

        <h1>Excluir conta</h1>

        <p>Tem certeza de que deseja excluir sua conta?</p>
        <p>Essa ação não poderá ser desfeita.</p>

        <?php if (!empty($mensagem)) : ?>
            <p>
                <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <!-- Formulário de confirmação: envia POST para a mesma página -->
        <form method="post" action="">
            <button type="submit">
                Sim, excluir minha conta
            </button>
        </form>

        <br>

        <!-- Botão de desistência → volta ao perfil -->
        <a href="perfil.php">Cancelar</a>

    </main>

    <!-- Rodapé global -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>
