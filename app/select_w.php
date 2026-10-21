<!-- =====================================================
     app/select_w.php — PESQUISA DE USUÁRIO POR E-MAIL
     (SELECT COM WHERE)
     O QUE FAZ: recebe um e-mail via GET, busca somente aquele
     usuário no banco (WHERE email = ...) e mostra os dados.
     POR QUÊ: página de pesquisa individual — o mesmo conceito
     que a área admin usa para localizar e excluir um usuário.
     ===================================================== -->
<?php

// Sessão ativa para o guarda de login.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Exige login; functions.php fornece $conexao.
require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../includes/functions.php';

// E-mail digitado no formulário (GET). ?? '' evita índice indefinido.
$email_pesquisa = trim($_GET['email'] ?? '');
$usuario = null;
$mensagem = '';

// Pesquisa quando um e-mail é informado.
if ($email_pesquisa != '') {

    // Valida o formato antes de consultar (bônus de segurança).
    if (!filter_var($email_pesquisa, FILTER_VALIDATE_EMAIL)) {

        $mensagem = "Informe um e-mail válido.";

    } else {

        // SELECT COM WHERE: só a linha do e-mail pesquisado.
        $sql = "SELECT name_sobrenome, email
                FROM usuarios
                WHERE email = :email";

        $stmt = $conexao->prepare($sql);
        $stmt->execute([':email' => $email_pesquisa]);

        // fetch(): UMA linha apenas (ou false se não achar).
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $mensagem = "Nenhum usuário encontrado com esse e-mail.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pesquisar usuário</title>
</head>
<body>

    <h1>Pesquisar usuário</h1>

    <!-- Formulário GET: o valor vai na URL (?email=...) -->
    <form method="GET">

        <div>
            <div>Digite o e-mail cadastrado:</div>

            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($email_pesquisa); ?>"
                required
            >
        </div>

        <br>
        <button type="submit">Pesquisar</button>

    </form>

    <?php if ($mensagem != ''): ?>
        <p><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <?php if ($usuario): ?>

        <h2>Dados encontrados</h2>

        <div>
            <div>Nome completo:</div>
            <div><?php echo htmlspecialchars($usuario['name_sobrenome']); ?></div>
        </div>

        <br>

        <div>
            <div>E-mail:</div>
            <div><?php echo htmlspecialchars($usuario['email']); ?></div>
        </div>

    <?php endif; ?>

    <br>
    <a href="perfil.php">Voltar ao perfil</a>

</body>
</html>
