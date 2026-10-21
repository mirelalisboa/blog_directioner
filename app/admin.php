<?php

// Sessão ativa antes de qualquer HTML (os redirecionamentos
// dependem disso).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Guarda de login: sem sessão → redireciona.
require_once __DIR__ . '/../includes/verificalogin.php';
// functions.php: todas as funções de admin + $conexao.
require_once __DIR__ . '/../includes/functions.php';

// Confere se o usuário conectado é administrador.
// (SELECT com WHERE pelo e-mail da sessão.)
$emailSessao = $_SESSION['email'] ?? '';

$sqlAdmin = "SELECT tipo FROM usuarios WHERE email = :email";
$stmtAdmin = $conexao->prepare($sqlAdmin);
$stmtAdmin->bindValue(':email', $emailSessao);
$stmtAdmin->execute();
$usuarioLogado = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

// Não é admin → nega o acesso (HTTP 403) e encerra.
// A coluna 'tipo' é 'admin' para contas de administração
// e 'usuario' (padrão) para contas comuns.
if (!$usuarioLogado || $usuarioLogado['tipo'] !== 'admin') {
    http_response_code(403);
    exit('Acesso negado. Esta página é exclusiva para administradores.');
}

$mensagem = '';

// Cadastra, atualiza ou exclui notícias
// (um único formulário/POST por operação, decidido por 'acao').
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    try {
        if ($acao === 'cadastrar') {
            // INSERT de nova notícia nos campos do formulário.
            $sql = "INSERT INTO noticias (titulo, noticia, foto, link)
                    VALUES (:titulo, :noticia, :foto, :link)";

            $stmt = $conexao->prepare($sql);
            $stmt->execute([
                ':titulo'  => trim($_POST['titulo'] ?? ''),
                ':noticia' => trim($_POST['noticia'] ?? ''),
                ':foto'    => trim($_POST['foto'] ?? ''),
                ':link'    => trim($_POST['link'] ?? '')
            ]);

            $mensagem = 'Notícia cadastrada com sucesso!';

        } elseif ($acao === 'editar') {
            // UPDATE da notícia pelo id (formulário da listagem).
            $sql = "UPDATE noticias
                    SET titulo = :titulo,
                        noticia = :noticia,
                        foto = :foto,
                        link = :link
                    WHERE id = :id";

            $stmt = $conexao->prepare($sql);
            $stmt->execute([
                ':titulo'  => trim($_POST['titulo'] ?? ''),
                ':noticia' => trim($_POST['noticia'] ?? ''),
                ':foto'    => trim($_POST['foto'] ?? ''),
                ':link'    => trim($_POST['link'] ?? ''),
                ':id'      => (int) ($_POST['id'] ?? 0)
            ]);

            $mensagem = 'Notícia atualizada!';

        } elseif ($acao === 'excluir') {
            // DELETE de notícia escolhida. Comentários vinculados
            // somem junto (ON DELETE CASCADE no banco).
            $sql = "DELETE FROM noticias WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([
                ':id' => (int) ($_POST['id'] ?? 0)
            ]);

            $mensagem = 'Notícia excluída!';

        } elseif ($acao === 'excluir_usuario') {

            // Exclusão de usuário pelo e-mail (tabela usuarios).
            // excluir_user() faz DELETE com prepared statement
            // e devolve true só se alguma linha foi afetada.
            if (excluir_user($conexao, trim($_POST['email'] ?? ''))) {
                $mensagem = 'Usuário excluído com sucesso!';
            } else {
                $mensagem = 'Não foi possível excluir o usuário (confira o e-mail).';
            }

        } elseif ($acao === 'excluir_comentario') {

            // DELETE de comentário de notícia (resultado da
            // pesquisa por integrante).
            $sql = "DELETE FROM comentarios WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([':id' => (int) ($_POST['id'] ?? 0)]);

            $mensagem = 'Comentário excluído!';

        } elseif ($acao === 'excluir_comentario_album') {

            // DELETE de comentário de álbum (resultado da
            // pesquisa por integrante).
            $sql = "DELETE FROM album_comentarios WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([':id' => (int) ($_POST['id'] ?? 0)]);

            $mensagem = 'Comentário de álbum excluído!';

        } elseif ($acao === 'excluir_album') {

            // DELETE de álbum inteiro (resultado da pesquisa por
            // integrante). Curtidas/favoritos/comentários do álbum
            // somem junto (ON DELETE CASCADE no banco).
            $sql = "DELETE FROM albuns WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([':id' => (int) ($_POST['id'] ?? 0)]);

            $mensagem = 'Álbum excluído!';
        }
    } catch (PDOException $erro) {
        $mensagem = 'Não foi possível concluir a operação. Verifique os dados.';
    }
}

// ------------------------------------------------------------------
// PESQUISA POR INTEGRANTE — tudo que há dele no site.
// Um nome (ex.: "Harry") é procurado nas tabelas possíveis:
// noticias (título/texto), comentarios (texto, com a notícia para
// contexto), albuns (título/descrição), album_comentarios (texto,
// com o álbum para contexto) e usuarios (nome/e-mail da conta).
// ILIKE = comparação sem diferenciar maiúsculas de minúsculas.
// ------------------------------------------------------------------
$nomeIntegrante = trim($_GET['busca_integrante'] ?? '');

// Cada lista começa vazia (a página funciona mesmo sem busca).
$integraNoticias   = []; // notícias que citam o nome
$integraComments   = []; // comentários de notícias que citam
$integraAlbuns     = []; // álbuns que citam
$integraAlbumComts = []; // comentários de álbuns que citam
$integraUsuarios   = []; // contas com o nome

if ($nomeIntegrante !== '') {
    try {
        $likeT = '%' . $nomeIntegrante . '%';

        // 1) Notícias: título OU texto traz o nome.
        $stmt = $conexao->prepare(
            "SELECT id, titulo, noticia
             FROM noticias
             WHERE titulo ILIKE :like_t OR noticia ILIKE :like_n
             ORDER BY id DESC"
        );
        $stmt->execute([':like_t' => $likeT, ':like_n' => $likeT]);
        $integraNoticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2) Comentários de notícias: texto traz o nome
        //    (LEFT JOIN traz a notícia-mãe para dar contexto).
        $stmt = $conexao->prepare(
            "SELECT c.id, c.comentario, c.noticia_id,
                    n.titulo AS noticia_titulo
             FROM comentarios c
             LEFT JOIN noticias n ON n.id = c.noticia_id
             WHERE c.comentario ILIKE :like
             ORDER BY c.id DESC"
        );
        $stmt->execute([':like' => $likeT]);
        $integraComments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3) Álbuns: título OU descrição traz o nome.
        $stmt = $conexao->prepare(
            "SELECT id, tipo, titulo, descricao
             FROM albuns
             WHERE titulo ILIKE :like_t OR descricao ILIKE :like_d
             ORDER BY id"
        );
        $stmt->execute([':like_t' => $likeT, ':like_d' => $likeT]);
        $integraAlbuns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 4) Comentários de álbuns: texto traz o nome
        //    (LEFT JOIN traz o álbum-mãe para dar contexto).
        $stmt = $conexao->prepare(
            "SELECT a.id, a.comentario, a.album_id,
                    b.titulo AS album_titulo
             FROM album_comentarios a
             LEFT JOIN albuns b ON b.id = a.album_id
             WHERE a.comentario ILIKE :like
             ORDER BY a.id DESC"
        );
        $stmt->execute([':like' => $likeT]);
        $integraAlbumComts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5) Contas de usuários: nome do perfil OU e-mail traz o nome.
        $stmt = $conexao->prepare(
            "SELECT id, name_sobrenome, email, tipo
             FROM usuarios
             WHERE name_sobrenome ILIKE :like_n OR email ILIKE :like_e
             ORDER BY name_sobrenome"
        );
        $stmt->execute([':like_n' => $likeT, ':like_e' => $likeT]);
        $integraUsuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $erro) {
        error_log("Erro ao pesquisar integrante: " . $erro->getMessage());
        // As listas continuam vazias — a página mostra "nada encontrado".
    }
}
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

    <!-- =====================================================
         CADASTRO DE NOTÍCIA (POST acao=cadastrar)
         ===================================================== -->
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

    <!-- =====================================================
         PESQUISA POR INTEGRANTE: um único campo de busca varre
         tudo que há dele no site — notícias, comentários,
         álbuns, comentários de álbuns e contas de usuário.
         Nada é listado adiantado: os resultados SÓ aparecem
         depois de pesquisar o nome. Cada resultado oferece
         sua exclusão, com confirmação.
         ===================================================== -->
    <h2>Pesquisar integrante (tudo que há dele no site)</h2>

    <form method="GET" action="">
        <p>
            Nome do integrante:
            <input type="text" name="busca_integrante"
                   value="<?= htmlspecialchars($nomeIntegrante) ?>"
                   placeholder="ex.: Harry, Zayn, Niall, Louis, Liam">
            <button type="submit">Pesquisar</button>
        </p>
    </form>

    <?php if ($nomeIntegrante !== ''): ?>

        <?php if (
            empty($integraNoticias) && empty($integraComments) &&
            empty($integraAlbuns) && empty($integraAlbumComts) &&
            empty($integraUsuarios)
        ): ?>
            <p>Nada encontrado para "<?= htmlspecialchars($nomeIntegrante) ?>".</p>
        <?php endif; ?>

        <!-- Notícias que citam o nome -->
        <?php foreach ($integraNoticias as $n): ?>
            <div>
                <p>
                    <strong>Notícia #<?= (int) $n['id'] ?> — <?= htmlspecialchars($n['titulo']) ?></strong><br>
                    <?= nl2br(htmlspecialchars(mb_substr($n['noticia'], 0, 120))) ?>…
                </p>
                <form method="POST"
                      onsubmit="return confirm('Excluir esta notícia e seus comentários?');">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id" value="<?= (int) $n['id'] ?>">
                    <button type="submit">Excluir notícia</button>
                </form>
                <hr>
            </div>
        <?php endforeach; ?>

        <!-- Comentários de notícias que citam o nome -->
        <?php foreach ($integraComments as $c): ?>
            <div>
                <p>
                    <strong>Comentário #<?= (int) $c['id'] ?></strong>
                    (na notícia "<?= htmlspecialchars($c['noticia_titulo'] ?? 'removida') ?>"):<br>
                    <?= htmlspecialchars($c['comentario']) ?>
                </p>
                <form method="POST"
                      onsubmit="return confirm('Excluir este comentário?');">
                    <input type="hidden" name="acao" value="excluir_comentario">
                    <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                    <button type="submit">Excluir comentário</button>
                </form>
                <hr>
            </div>
        <?php endforeach; ?>

        <!-- Álbuns que citam o nome -->
        <?php foreach ($integraAlbuns as $a): ?>
            <div>
                <p>
                    <strong>Álbum #<?= (int) $a['id'] ?> — <?= htmlspecialchars($a['titulo']) ?></strong>
                    (tipo <?= htmlspecialchars($a['tipo']) ?>):<br>
                    <?= nl2br(htmlspecialchars(mb_substr($a['descricao'], 0, 120))) ?>…
                </p>
                <form method="POST"
                      onsubmit="return confirm('Excluir este álbum e todas as suas curtidas/comentários?');">
                    <input type="hidden" name="acao" value="excluir_album">
                    <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
                    <button type="submit">Excluir álbum</button>
                </form>
                <hr>
            </div>
        <?php endforeach; ?>

        <!-- Comentários de álbuns que citam o nome -->
        <?php foreach ($integraAlbumComts as $c): ?>
            <div>
                <p>
                    <strong>Comentário de álbum #<?= (int) $c['id'] ?></strong>
                    (no álbum "<?= htmlspecialchars($c['album_titulo'] ?? 'removido') ?>"):<br>
                    <?= htmlspecialchars($c['comentario']) ?>
                </p>
                <form method="POST"
                      onsubmit="return confirm('Excluir este comentário?');">
                    <input type="hidden" name="acao" value="excluir_comentario_album">
                    <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                    <button type="submit">Excluir comentário</button>
                </form>
                <hr>
            </div>
        <?php endforeach; ?>

        <!-- Contas de usuário com o nome -->
        <?php foreach ($integraUsuarios as $u): ?>
            <div>
                <p>
                    <strong>Usuário #<?= (int) $u['id'] ?> — <?= htmlspecialchars($u['name_sobrenome']) ?></strong><br>
                    E-mail: <?= htmlspecialchars($u['email']) ?> ·
                    Tipo: <?= htmlspecialchars($u['tipo']) ?>
                </p>
                <form method="POST"
                      onsubmit="return confirm('Excluir esta conta definitivamente?');">
                    <input type="hidden" name="acao" value="excluir_usuario">
                    <input type="hidden" name="email"
                           value="<?= htmlspecialchars($u['email']) ?>">
                    <button type="submit">Excluir usuário</button>
                </form>
                <hr>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

    <p><a href="../pages/noticias.php">Voltar para as notícias</a></p>
    <p><a href="/projetointegrador/login/logout.php">Sair da conta</a></p>

</body>
</html>
