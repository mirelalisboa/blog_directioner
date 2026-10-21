<!-- =====================================================
     app/delete.php — EXCLUIR MINHA CONTA
     O QUE FAZ: pede confirmação (POST) e apaga a própria conta
     do banco, encerrando a sessão em seguida.
     POR QUÊ: acessado pelo botão "Excluir conta" de app/perfil.php.
     FLUXO: guardas de login → POST confirma → excluir_user()
     (functions.php, DELETE por e-mail) → sessão destruída →
     volta para a página inicial (index.php).
     ===================================================== -->
<?php

// Sessão: precisa estar ativa antes de qualquer HTML (headers).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Guardas de login: exige usuário conectado.
require_once __DIR__ . '/../includes/verificalogin.php';
// functions.php: excluir_user() + $conexao.
require_once __DIR__ . '/../includes/functions.php';
// Reforço do guarda (same key: $_SESSION['id']).
require_once __DIR__ . '/../login/verificauser.php';

// Verifica se existe um e-mail na sessão.
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: /projetointegrador/login/cadastro.php");
    exit();
}

$mensagem = "";

// Só exclui a conta quando o formulário for enviado (POST):
// isso evita apagar a conta num simples acesso à página (GET).
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Guarda o e-mail da conta conectada.
    $email = $_SESSION['email'];

    // Exclui a conta pelo e-mail (DELETE com prepared statement).
    if (excluir_user($conexao, $email)) {

        // Encerra a sessão após excluir a conta:
        // nada mais para fazer aqui — nem perfil nem menu logado.
        $_SESSION = [];
        session_destroy();

        // Conta apagada: o usuário não está mais logado, então volta
        // para a página inicial do site (index.php).
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
