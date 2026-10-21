<!-- =====================================================
     app/select.php — LISTAGEM DE TODOS OS USUÁRIOS
     O QUE FAZ: SELECT simples (sem WHERE), ORDER BY nome,
     exibe a lista completa em tabela de consulta.
     POR QUÊ: página de relatório/consulta rápida dos cadastros
     (não é a área admin; a lista do admin fica em admin.php).
     ===================================================== -->
<?php

// Sessão ativa para o guarda de login.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Exige login; functions.php fornece $conexao.
require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../includes/functions.php';

// Consulta os usuários cadastrados.
// (SELECT sem WHERE: devolve todas as linhas da tabela.)
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

        <!-- Tabela simples de relatório (border=1 no estilo antigo) -->
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
