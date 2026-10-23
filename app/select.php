<!-- lista todos os usuarios cadastrados (tabela de consulta) -->
<?php

// sessao pro arquivo de login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// exige login; functions.php traz a conexao
require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../includes/functions.php';

// pega todos os usuarios do banco (select sem where)
$sql = "SELECT name_sobrenome, email
        FROM usuarios
        ORDER BY name_sobrenome";

$stmt = $conexao->prepare($sql);
$stmt->execute();

// fetchAll: monta um array com TODAS as linhas de uma vez.
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultar usuários</title>
</head>
<body>

    <h1>Usuários cadastrados</h1>

    <?php if ($usuarios): ?>

        <!-- tabela de consulta -->
        <table border="1">
            <tr>
                <th>Nome completo</th>
                <th>E-mail</th>
            </tr>

            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td>
                        <!-- htmlspecialchars: impede que dados do
                             banco quebrem o HTML (XSS) -->
                        <?php echo htmlspecialchars($usuario['name_sobrenome']); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($usuario['email']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php else: ?>

        <p>Nenhum usuário cadastrado.</p>

    <?php endif; ?>

    <br>
    <a href="perfil.php">Voltar ao perfil</a>

</body>
</html>
