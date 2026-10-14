
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../includes/functions.php';

$email_pesquisa = trim($_GET['email'] ?? '');
$usuario = null;
$mensagem = '';

// Pesquisa quando um e-mail é informado.
if ($email_pesquisa != '') {

    if (!filter_var($email_pesquisa, FILTER_VALIDATE_EMAIL)) {

        $mensagem = "Informe um e-mail válido.";

    } else {

        $sql = "SELECT name_sobrenome, email
                FROM usuarios
                WHERE email = :email";

        $stmt = $conexao->prepare($sql);
        $stmt->execute([':email' => $email_pesquisa]);

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