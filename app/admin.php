
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/verificar_login.php';
require_once __DIR__ . '/../includes/functions.php';

// Confere se o usuário conectado é administrador
$emailSessao = $_SESSION['email'] ?? '';

$sqlAdmin = "SELECT tipo FROM usuarios WHERE email = :email";
$stmtAdmin = $conexao->prepare($sqlAdmin);
$stmtAdmin->bindValue(':email', $emailSessao);
$stmtAdmin->execute();
$usuarioLogado = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

if (!$usuarioLogado || $usuarioLogado['tipo'] !== 'admin') {
    http_response_code(403);
    exit('Acesso negado. Esta página é exclusiva para administradores.');
}

$mensagem = '';

// Cadastra, atualiza ou exclui notícias
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    try {
        if ($acao === 'cadastrar') {
            $sql = "INSERT INTO noticias (titulo, noticia, foto, link)
                    VALUES (:titulo, :noticia, :foto, :link)";

            $stmt = $conexao->prepare($sql);
            $stmt->execute([
                ':titulo' => trim($_POST['titulo'] ?? ''),
                ':noticia' => trim($_POST['noticia'] ?? ''),
                ':foto' => trim($_POST['foto'] ?? ''),
                ':link' => trim($_POST['link'] ?? '')
            ]);

            $mensagem = 'Notícia cadastrada com sucesso!';

        } elseif ($acao === 'editar') {
            $sql = "UPDATE noticias
                    SET titulo = :titulo,
                        noticia = :noticia,
                        foto = :foto,
                        link = :link
                    WHERE id = :id";

            $stmt = $conexao->prepare($sql);
            $stmt->execute([
                ':titulo' => trim($_POST['titulo'] ?? ''),
                ':noticia' => trim($_POST['noticia'] ?? ''),
                ':foto' => trim($_POST['foto'] ?? ''),
                ':link' => trim($_POST['link'] ?? ''),
                ':id' => (int) ($_POST['id'] ?? 0)
            ]);

            $mensagem = 'Notícia atualizada!';

        } elseif ($acao === 'excluir') {
            $sql = "DELETE FROM noticias WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([
                ':id' => (int) ($_POST['id'] ?? 0)
            ]);

            $mensagem = 'Notícia excluída!';
        }
    } catch (PDOException $erro) {
        $mensagem = 'Não foi possível concluir a operação. Verifique os dados.';
    }
}

// Busca as notícias cadastradas
$stmtNoticias = $conexao->query(
    "SELECT id, titulo, noticia, foto, link
     FROM noticias ORDER BY id DESC"
);
$noticias = $stmtNoticias->fetchAll(PDO::FETCH_ASSOC);

// Busca os usuários cadastrados
$stmtUsuarios = $conexao->query(
    "SELECT name_sobrenome, email, tipo
     FROM usuarios ORDER BY name_sobrenome"
);
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador | One Direction</title>
</head>
<body>

    <h1>Área do Administrador</h1>
    <p>Bem-vindo à administração do blog One Direction!</p>

    <?php if ($mensagem !== ''): ?>
        <p><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <hr>

    <h2>Cadastrar notícia</h2>

    <form method="POST">
        <input type="hidden" name="acao" value="cadastrar">

        <div>
            <p>Título da notícia</p>
            <input type="text" name="titulo" required>
        </div>

        <div>
            <p>Conteúdo da notícia</p>
            <textarea name="noticia" required></textarea>
        </div>

        <div>
            <p>Foto (endereço ou caminho da imagem)</p>
            <input type="text" name="foto">
        </div>

        <div>
            <p>Link da notícia original</p>
            <input type="url" name="link">
        </div>

        <button type="submit">Cadastrar notícia</button>
    </form>

    <hr>

    <h2>Notícias cadastradas</h2>

    <?php foreach ($noticias as $n): ?>
        <div>
            <h3><?= htmlspecialchars($n['titulo']) ?></h3>
            <p><?= nl2br(htmlspecialchars($n['noticia'])) ?></p>

            <form method="POST">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id"
                       value="<?= (int) $n['id'] ?>">

                <div>
                    <p>Título</p>
                    <input type="text" name="titulo"
                           value="<?= htmlspecialchars($n['titulo']) ?>"
                           required>
                </div>

                <div>
                    <p>Conteúdo</p>
                    <textarea name="noticia" required><?= htmlspecialchars($n['noticia']) ?></textarea>
                </div>

                <div>
                    <p>Foto</p>
                    <input type="text" name="foto"
                           value="<?= htmlspecialchars($n['foto'] ?? '') ?>">
                </div>

                <div>
                    <p>Link original</p>
                    <input type="url" name="link"
                           value="<?= htmlspecialchars($n['link'] ?? '') ?>">
                </div>

                <button type="submit">Salvar alterações</button>
            </form>

            <form method="POST"
                  onsubmit="return confirm('Deseja excluir esta notícia?');">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id"
                       value="<?= (int) $n['id'] ?>">
                <button type="submit">Excluir notícia</button>
            </form>

            <hr>
        </div>
    <?php endforeach; ?>

    <h2>Usuários cadastrados</h2>

    <?php foreach ($usuarios as $u): ?>
        <div>
            <p>
                Nome: <?= htmlspecialchars($u['name_sobrenome']) ?><br>
                E-mail: <?= htmlspecialchars($u['email']) ?><br>
                Tipo: <?= htmlspecialchars($u['tipo']) ?>
            </p>
        </div>
    <?php endforeach; ?>

    <p><a href="../pages/noticias.php">Voltar para as notícias</a></p>
    <p><a href="logout.php">Sair da conta</a></p>

</body>
</html>

