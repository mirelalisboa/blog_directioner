<!-- =====================================================
     app/create.php — ADICIONAR NOTÍCIA OU COMENTÁRIO (legado)
     O QUE FAZ: formulário completo para gravar notícias na
     tabela noticias e comentários na tabela comentarios.

     ===================================================== -->
<?php

// Sessão: guardamos id/usuario/email do usuário logado nela.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Importa as funções (que carregam a conexão) e o guarda de login.
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verificauser.php';

$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe o tipo de publicação (noticia ou comentario),
    // decide a operação pelo valor do <select>.
    $tipo = $_POST['tipo'] ?? '';

    try {

        // Cadastra uma notícia
        if ($tipo === 'noticia') {

            $titulo  = trim($_POST['titulo'] ?? '');
            $noticia = trim($_POST['noticia'] ?? '');
            $foto    = trim($_POST['foto'] ?? '');
            $link    = trim($_POST['link'] ?? '');

            // Título e texto são obrigatórios (foto/link, não)
            if ($titulo === '' || $noticia === '') {
                throw new Exception('Preencha o título e a notícia.');
            }

            // INSERT com prepared statement (valores em :parametros,
            // nunca concatenados na string SQL contra injeção).
            $sql = "INSERT INTO noticias (titulo, noticia, foto, link)
                    VALUES (:titulo, :noticia, :foto, :link)";

            $stmt = $conexao->prepare($sql);

            $stmt->execute([
                ':titulo'  => $titulo,
                ':noticia' => $noticia,
                ':foto'    => $foto,
                ':link'    => $link
            ]);

            $mensagem = 'Notícia adicionada com sucesso!';
        }

        // Adiciona um comentário
        elseif ($tipo === 'comentario') {

            // Valida o id da notícia escolhida como inteiro,
            // evita textos/valores maliciosos no campo.
            $noticia_id = filter_input(
                INPUT_POST,
                'noticia_id',
                FILTER_VALIDATE_INT
            );

            $comentario = trim($_POST['comentario'] ?? '');

            if (!$noticia_id || $comentario === '') {
                throw new Exception('Selecione uma notícia e escreva o comentário.');
            }

            // Verifica se a notícia existe (antes de gravar).
            $verifica = $conexao->prepare(
                "SELECT id FROM noticias WHERE id = :id"
            );

            $verifica->execute([':id' => $noticia_id]);

            if (!$verifica->fetch()) {
                throw new Exception('Notícia não encontrada.');
            }

            // Salva o comentário sem cadastrar um usuário
            // (comentário anônimo vinculado à notícia).
            $sql = "INSERT INTO comentarios (noticia_id, comentario)
                    VALUES (:noticia_id, :comentario)";

            $stmt = $conexao->prepare($sql);

            $stmt->execute([
                ':noticia_id' => $noticia_id,
                ':comentario' => $comentario
            ]);

            $mensagem = 'Comentário adicionado com sucesso!';
        }

        else {
            throw new Exception('Selecione uma opção válida.');
        }

    } catch (PDOException $e) {
        $mensagem = 'Erro ao salvar. Verifique as tabelas do banco de dados.';
    } catch (Exception $e) {
        $mensagem = $e->getMessage();
    }
}

// Busca as notícias disponíveis
// (alimentam o <select> de escolha do comentário)
$stmt = $conexao->query(
    "SELECT id, titulo FROM noticias ORDER BY id DESC"
);

$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar notícia ou comentário</title>
</head>
<body>

    <h1>Blog One Direction</h1>
    <h2>Adicionar notícias ou comentários</h2>

    <!-- Mostra a mensagem da operação (sucesso/erro) -->
    <?php if ($mensagem !== ''): ?>
        <p><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <!-- Um único formulário com dois "modos" (campo tipo) -->
    <form method="POST" action="">

        <label for="tipo">Escolha o que deseja adicionar:</label>

        <select name="tipo" id="tipo" required>
            <option value="">Selecione</option>
            <option value="noticia">Notícia</option>
            <option value="comentario">Comentário</option>
        </select>

        <hr>

        <!-- Campos para adicionar notícias -->
        <h3>Adicionar notícia</h3>

        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo">

        <br><br>

        <label for="noticia">Notícia:</label>
        <textarea name="noticia" id="noticia" rows="5"></textarea>

        <br><br>

        <label for="foto">Foto:</label>
        <input type="text" name="foto" id="foto">

        <br><br>

        <label for="link">Link original:</label>
        <input type="url" name="link" id="link">

        <hr>

        <!-- Campos para adicionar comentários -->
        <h3>Adicionar comentário</h3>

        <label for="noticia_id">Notícia:</label>

        <!-- Lista de notícias do banco para escolher a que comenta -->
        <select name="noticia_id" id="noticia_id">
            <option value="">Selecione uma notícia</option>

            <?php foreach ($noticias as $item): ?>
                <option value="<?= (int) $item['id'] ?>">
                    <?= htmlspecialchars($item['titulo'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <label for="comentario">Comentário:</label>
        <textarea name="comentario" id="comentario" rows="4"></textarea>

        <br><br>

        <button type="submit">Salvar</button>

    </form>

    <p><a href="../noticias.php">Voltar para notícias</a></p>

</body>
</html>
