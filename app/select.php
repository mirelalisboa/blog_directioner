
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../includes/functions.php';

// Consulta os usuários cadastrados.
$sql = "SELECT name_sobrenome, email
        FROM usuarios
        ORDER BY name_sobrenome";

$stmt = $conexao->prepare($sql);
$stmt->execute();

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

        <table border="1">
            <tr>
                <th>Nome completo</th>
                <th>E-mail</th>
            </tr>

            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td>
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