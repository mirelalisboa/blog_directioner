
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/verificar_login.php';
;

// Só permite continuar se o usuário estiver conectado
if (!isset($_SESSION['id'])) {
    header("Location: ../login/login.php");
    exit();
}

$id = $_SESSION['id'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir minha conta</title>
</head>

<body>

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>

        <h1>Excluir minha conta</h1>

        <p>Tem certeza de que deseja excluir sua conta?</p>
        <p>Essa ação não poderá ser desfeita.</p>

        <form action="" method="post">

            <input type="submit"
                   name="confirmar"
                   value="Sim, excluir minha conta">

            <input type="button"
                   value="Cancelar"
                   onclick="window.location.href='../app/create.php'">

        </form>

        <?php

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['confirmar'])) {

            try {

                // Exclui somente a conta que está conectada
                $sql = "DELETE FROM usuarios WHERE email && senha = :email, :senha";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                // Encerra a sessão depois da exclusão
                $_SESSION = [];
                session_destroy();

                header("Location: ../paginalogin.php");
                exit();

            } catch (PDOException $e){


            
        }

        }

        ?>

    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>