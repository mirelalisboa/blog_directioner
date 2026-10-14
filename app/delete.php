
<?php

// Inicia a sessão, caso ainda não esteja iniciada.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/verificar_login.php';
require_once __DIR__ . '/../includes/functions.php';

// Verifica se existe um e-mail na sessão.
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: cadastro.php");
    exit();
}

$mensagem = "";

// Só exclui a conta quando o formulário for enviado.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Guarda o e-mail da conta conectada.
    $email = $_SESSION['email'];

    // Exclui a conta pelo e-mail.
    if (excluir_user($conexao, $email)) {

        // Encerra a sessão após excluir a conta.
        $_SESSION = [];
        session_destroy();

        header("Location: cadastro.php");
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

        <form method="post" action="">
            <button type="submit">
                Sim, excluir minha conta
            </button>
        </form>

        <br>

        <a href="perfil.php">Cancelar</a>

    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>
