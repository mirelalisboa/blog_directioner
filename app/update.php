<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/verificar_login.php';
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['email'])) {
    echo "Faça login para atualizar sua conta.";
    exit();
}

$email_atual = $_SESSION['email'];
$mensagem = "";

// Busca os dados da conta conectada.
$sql = "SELECT name_sobrenome, email, foto_perfil
        FROM usuarios
        WHERE email = :email";

$stmt = $conexao->prepare($sql);
$stmt->execute([':email' => $email_atual]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['name_sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Mantém a foto atual se nenhuma nova for enviada.
    $foto_perfil = $usuario['foto_perfil'] ?? null;
    $nova_foto_salva = null;

    if ($nome == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Informe um nome e um e-mail válido.";
    }

    // Verifica se o e-mail pertence a outra conta.
    if ($mensagem == "") {

        $sql = "SELECT email FROM usuarios
                WHERE email = :email
                AND email <> :email_atual";

        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':email_atual' => $email_atual
        ]);

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $mensagem = "Esse e-mail já está sendo usado por outro usuário.";
        }
    }

    // Processa a foto somente se o usuário escolher uma.
    if (
        $mensagem == "" &&
        isset($_FILES['foto_perfil']) &&
        $_FILES['foto_perfil']['error'] != UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['foto_perfil']['error'] != UPLOAD_ERR_OK) {
            $mensagem = "Ocorreu um erro ao enviar a foto.";
        } elseif ($_FILES['foto_perfil']['size'] > 5 * 1024 * 1024) {
            $mensagem = "A foto deve ter no máximo 5 MB.";
        } else {

            // Aceita somente imagens JPG, PNG ou WEBP.
            $tiposPermitidos = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp'
            ];

            $tipo = (new finfo(FILEINFO_MIME_TYPE))
                ->file($_FILES['foto_perfil']['tmp_name']);

            if (!isset($tiposPermitidos[$tipo])) {
                $mensagem = "Escolha uma imagem JPG, PNG ou WEBP.";
            } else {

                $pasta = __DIR__ . '/../imagens/perfis/';

                if (
                    !is_dir($pasta) &&
                    !mkdir($pasta, 0755, true)
                ) {

                    $mensagem = "Não foi possível criar a pasta das fotos.";
                } else {

                    // Gera um nome aleatório para evitar conflitos.
                    $nomeArquivo = bin2hex(random_bytes(16))
                        . '.' . $tiposPermitidos[$tipo];

                    $destino = $pasta . $nomeArquivo;

                    if (move_uploaded_file(
                        $_FILES['foto_perfil']['tmp_name'],
                        $destino
                    )) {
                        $foto_perfil = $nomeArquivo;
                        $nova_foto_salva = $destino;
                    } else {
                        $mensagem = "Não foi possível salvar a foto.";
                    }
                }
            }
        }
    }

    // Salva os dados somente se não houver erros.
    if ($mensagem == "") {

        try {

            if ($senha != "") {

                // Atualiza nome, e-mail, senha e foto.
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = "UPDATE usuarios
                        SET name_sobrenome = :nome,
                            email = :email,
                            senha = :senha,
                            foto_perfil = :foto
                        WHERE email = :email_atual";

                $stmt = $conexao->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $senha_hash,
                    ':foto' => $foto_perfil,
                    ':email_atual' => $email_atual
                ]);
            } else {

                // Mantém a senha atual se o campo estiver vazio.
                $sql = "UPDATE usuarios
                        SET name_sobrenome = :nome,
                            email = :email,
                            foto_perfil = :foto
                        WHERE email = :email_atual";

                $stmt = $conexao->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':foto' => $foto_perfil,
                    ':email_atual' => $email_atual
                ]);
            }

            // Atualiza a sessão com os novos dados.
            $_SESSION['usuario'] = $nome;
            $_SESSION['email'] = $email;

            header("Location: perfil.php");
            exit();
        } catch (PDOException $e) {

            // Remove a nova imagem se o banco não aceitar a atualização.
            if (
                $nova_foto_salva !== null &&
                is_file($nova_foto_salva)
            ) {
                unlink($nova_foto_salva);
            }

            $mensagem = "Não foi possível atualizar a conta.";
        }
    }

    // Mantém os dados preenchidos no formulário em caso de erro.
    $usuario['name_sobrenome'] = $nome;
    $usuario['email'] = $email;

    if ($mensagem != "" && $nova_foto_salva !== null) {
        $usuario['foto_perfil'] = $foto_perfil;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar minha conta</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
            background-color: #f5f5f5;
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
            width: 110px;
            height: 110px;
            margin: 0 auto 15px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            background-color: #dddddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            border: 3px solid #cccccc;
        }

        .foto-perfil img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .campo {
            margin: 15px 0;
            text-align: left;
        }

        .campo div {
            margin-bottom: 6px;
            font-weight: bold;
        }

        .campo input {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 6px;
        }

        button {
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background-color: #222222;
            color: white;
        }

        .mensagem {
            color: #b00020;
            margin: 12px 0;
        }

        .ajuda {
            font-size: 13px;
            color: #666666;
        }
    </style>
</head>

<body>

    <div class="perfil">

        <h1>Minha conta</h1>

        <!-- Clicar na foto abre ou fecha o formulário. -->
        <div
            class="foto-perfil"
            role="button"
            tabindex="0"
            aria-label="Abrir edição dos dados da conta"
            onclick="alternarEdicao()"
            onkeydown="if(event.key === 'Enter' || event.key === ' '){event.preventDefault(); alternarEdicao();}">
            <?php if (!empty($usuario['foto_perfil'])): ?>
                <img
                    src="../imagens/perfis/<?php echo rawurlencode($usuario['foto_perfil']); ?>"
                    alt="Foto de perfil">
            <?php else: ?>
                👤
            <?php endif; ?>
        </div>

        <div>
            <?php echo htmlspecialchars($usuario['name_sobrenome']); ?>
        </div>

        <div>
            <?php echo htmlspecialchars($usuario['email']); ?>
        </div>

        <p>Clique na foto para editar seus dados.</p>

        <?php if ($mensagem != ""): ?>
            <div class="mensagem">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário para atualizar a conta. -->
        <div id="edicao" <?php echo $mensagem != "" ? '' : 'hidden'; ?>>

            <h2>Editar meus dados</h2>

            <form method="POST" enctype="multipart/form-data">

                <div class="campo">
                    <div>Foto de perfil</div>
                    <input
                        type="file"
                        name="foto_perfil"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="ajuda">JPG, PNG ou WEBP. Máximo de 5 MB.</div>
                </div>

                <div class="campo">
                    <div>Nome completo</div>
                    <input
                        type="text"
                        name="name_sobrenome"
                        value="<?php echo htmlspecialchars($usuario['name_sobrenome']); ?>"
                        required>
                </div>

                <div class="campo">
                    <div>E-mail</div>
                    <input
                        type="email"
                        name="email"
                        value="<?php echo htmlspecialchars($usuario['email']); ?>"
                        required>
                </div>

                <div class="campo">
                    <div>Nova senha</div>
                    <input
                        type="password"
                        name="senha"
                        autocomplete="new-password"
                        placeholder="Deixe vazio para manter a senha atual">
                    <div class="ajuda">
                        Preencha somente se quiser trocar a senha.
                    </div>
                </div>

                <button type="submit">Salvar alterações</button>

            </form>
        </div>

        <br>
        <a href="perfil.php">Voltar ao perfil</a>

    </div>

    <script>
        function alternarEdicao() {
            const edicao = document.getElementById('edicao');
            edicao.hidden = !edicao.hidden;
        }
    </script>

</body>

</html>