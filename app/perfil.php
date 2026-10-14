
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/verificalogin.php';
require_once __DIR__ . '/../includes/functions.php';


// Busca os dados do usuário pelo e-mail da sessão.
$sql = "SELECT name_sobrenome, email, foto_perfil
        FROM usuarios
        WHERE email = :email";

$stmt = $conexao->prepare($sql);
$stmt->execute([
    ':email' => $_SESSION['email']
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Não foi possível encontrar seu perfil.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu perfil</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 30px;
        }

        .perfil {
            max-width: 500px;
            margin: auto;
            padding: 25px;
            background-color: white;
            border-radius: 12px;
            text-align: center;
        }

        .foto-perfil {
            width: 130px;
            height: 130px;
            margin: 20px auto;
            border-radius: 50%;
            overflow: hidden;
            background-color: #dddddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
        }

        .foto-perfil img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .informacao {
            margin: 15px 0;
        }

        .botoes {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .botoes a {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 6px;
            text-decoration: none;
            background-color: #222222;
            color: white;
        }
    </style>
</head>

<body>

<div class="perfil">

    <h1>Meu perfil</h1>

    <div class="foto-perfil">
        <?php if (!empty($usuario['foto_perfil'])): ?>

            <img
                src="../imagens/perfis/<?php echo rawurlencode($usuario['foto_perfil']); ?>"
                alt="Foto de perfil de <?php echo htmlspecialchars($usuario['name_sobrenome']); ?>"
            >

        <?php else: ?>

            👤

        <?php endif; ?>
    </div>

    <div class="informacao">
        <strong>Nome:</strong>
        <?php echo htmlspecialchars($usuario['name_sobrenome']); ?>
    </div>

    <div class="informacao">
        <strong>E-mail:</strong>
        <?php echo htmlspecialchars($usuario['email']); ?>
    </div>

    <div class="botoes">
        <a href="update.php">Atualizar conta</a>
        <a href="delete.php">Excluir conta</a>
    </div>

</div>

</body>
</html>