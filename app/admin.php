<?php

// sessao ativa antes de qualquer html
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// exige login
require_once __DIR__ . '/../includes/verificalogin.php';
// functions.php traz as funcoes de admin e a conexao
require_once __DIR__ . '/../includes/functions.php';

// ve se quem ta logado e admin
$emailSessao = $_SESSION['email'] ?? '';

$sqlAdmin = "SELECT tipo FROM usuarios WHERE email = :email";
$stmtAdmin = $conexao->prepare($sqlAdmin);
$stmtAdmin->bindValue(':email', $emailSessao);
$stmtAdmin->execute();
$usuarioLogado = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

// quem nao e admin recebe erro 403 e para
// (coluna tipo: 'admin' pra conta de admin, 'usuario' comum)
if (!$usuarioLogado || $usuarioLogado['tipo'] !== 'admin') {
    http_response_code(403);
    exit('Acesso negado. Esta página é exclusiva para administradores.');
}

$mensagem = '';

// o campo 'acao' decide o que o post faz
// (cadastrar/editar/excluir noticia ou usuario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    try {
        if ($acao === 'cadastrar') {
            // cadastra a noticia nova
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
            // edita a noticia pelo id
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
            // exclui a noticia (os comentarios somem junto no banco)
            $sql = "DELETE FROM noticias WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([
                ':id' => (int) ($_POST['id'] ?? 0)
            ]);

            $mensagem = 'Notícia excluída!';

        } elseif ($acao === 'excluir_usuario') {

            // exclui o usuario pelo email (true se conseguiu)
            if (excluir_user($conexao, trim($_POST['email'] ?? ''))) {
                $mensagem = 'Usuário excluído com sucesso!';
            } else {
                $mensagem = 'Não foi possível excluir o usuário (confira o e-mail).';
            }

        } elseif ($acao === 'excluir_comentario') {

            // exclui comentario de noticia
            $sql = "DELETE FROM comentarios WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([':id' => (int) ($_POST['id'] ?? 0)]);

            $mensagem = 'Comentário excluído!';

        } elseif ($acao === 'excluir_comentario_album') {

            // exclui comentario de album
            $sql = "DELETE FROM album_comentarios WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([':id' => (int) ($_POST['id'] ?? 0)]);

            $mensagem = 'Comentário de álbum excluído!';

        } elseif ($acao === 'excluir_album') {

            // exclui o album inteiro (curtidas e comentarios
            // dele somem junto no banco)
            $sql = "DELETE FROM albuns WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([':id' => (int) ($_POST['id'] ?? 0)]);

            $mensagem = 'Álbum excluído!';
        }
    } catch (PDOException $erro) {
        $mensagem = 'Não foi possível concluir a operação. Verifique os dados.';
    }
}

// pesquisa por integrante: procura o nome em noticias,
// comentarios, albuns e contas de usuario
// (ILIKE ignora maiusculas/minusculas)
$nomeIntegrante = trim($_GET['busca_integrante'] ?? '');

// listas comecam vazias (a pagina funciona sem busca)
$integraNoticias   = []; // notícias que citam o nome
$integraComments   = []; // comentários de notícias que citam
$integraAlbuns     = []; // álbuns que citam
$integraAlbumComts = []; // comentários de álbuns que citam
$integraUsuarios   = []; // contas com o nome

if ($nomeIntegrante !== '') {
    try {
        $likeT = '%' . $nomeIntegrante . '%';

        // 1) noticias: nome no titulo ou no texto
        $stmt = $conexao->prepare(
            "SELECT id, titulo, noticia
             FROM noticias
             WHERE titulo ILIKE :like_t OR noticia ILIKE :like_n
             ORDER BY id DESC"
        );
        $stmt->execute([':like_t' => $likeT, ':like_n' => $likeT]);
        $integraNoticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2) comentarios de noticias (join pega a noticia pro contexto)
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

        // 3) albuns: nome no titulo ou na descricao
        $stmt = $conexao->prepare(
            "SELECT id, tipo, titulo, descricao
             FROM albuns
             WHERE titulo ILIKE :like_t OR descricao ILIKE :like_d
             ORDER BY id"
        );
        $stmt->execute([':like_t' => $likeT, ':like_d' => $likeT]);
        $integraAlbuns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 4) comentarios de albuns (join pega o album pro contexto)
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

        // 5) contas com o nome ou o email
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
        // erro vai pro log e as listas ficam vazias ("nada encontrado")
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador | One Direction</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Federo&display=swap');

        /* =========================================================
           PALETAS DE CORES DISPONÍVEIS
           ========================================================= */
        :root {
            /* Paleta 4: Azul-marinho, Cinza e Branco */
            --p4-navy: #142B4A;
            --p4-slate-gray: #708090;
            --p4-ice-white: #E8EDF2;

            /* Mapeamento de variáveis */
            --fundo: var(--p4-navy);           /* Fundo principal */
            --card: #1C365D;                  /* Fundo das caixas e cartões */
            --azul: var(--p4-slate-gray);     /* Bordas e linhas */
            --azul-claro: #A9C4CE;            /* Destaques e links */
            --marrom: #2B4566;                /* Botões principais */
            --marrom-claro: #385B87;          /* Hover dos botões */
            --cinza: var(--p4-slate-gray);    /* Linhas divisórias */
            --texto: var(--p4-ice-white);     /* Texto principal */
            --texto-suave: #B0C0D0;           /* Rótulos e textos secundários */
            --vermelho-erro: #8B2626;         /* Botão de exclusão */
            --vermelho-hover: #A83232;        /* Hover de exclusão */
        }

        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--fundo);
            color: var(--texto);
            font-family: Georgia, 'Times New Roman', Times, serif;
            padding: 20px 15px;
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            color: var(--texto);
            text-align: center;
            font-size: 1.8em;
            margin: 20px 0 10px;
        }

        h2 {
            color: var(--azul-claro);
            font-size: 1.3em;
            margin: 25px 0 15px;
            border-bottom: 1px solid var(--azul);
            padding-bottom: 5px;
        }

        body > p {
            text-align: center;
            color: var(--texto-suave);
            font-size: 0.95em;
            margin-bottom: 20px;
        }

        /* Mensagens de confirmação e erro */
        .mensagem {
            background-color: var(--card);
            border: 1px solid var(--azul-claro);
            color: var(--texto);
            padding: 10px 15px;
            border-radius: 8px;
            text-align: center;
            margin: 15px auto;
            max-width: 600px;
        }

        /* Formulários */
        form {
            background-color: var(--card);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid var(--azul);
            margin-bottom: 20px;
        }

        form div {
            margin-bottom: 15px;
        }

        form p {
            color: var(--texto-suave);
            font-weight: bold;
            font-size: 0.9em;
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--azul);
            border-radius: 6px;
            background-color: #0E1E34;
            color: var(--texto);
            font-family: inherit;
            font-size: 0.9em;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="url"]:focus,
        textarea:focus {
            border-color: var(--azul-claro);
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        /* Botões */
        button[type="submit"] {
            background-color: var(--marrom);
            color: var(--texto);
            border: 1px solid var(--azul);
            padding: 9px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.88em;
            font-weight: bold;
            transition: all 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: var(--marrom-claro);
        }

        /* Botões de exclusão dentro das listas */
        div > form button[type="submit"] {
            background-color: var(--vermelho-erro);
            border-color: #B23B3B;
            margin-top: 8px;
        }

        div > form button[type="submit"]:hover {
            background-color: var(--vermelho-hover);
        }

        /* Blocos de resultados da busca */
        div > p {
            text-align: left;
            color: var(--texto);
            font-size: 0.9em;
            line-height: 1.5;
        }

        /* Linhas divisórias */
        hr {
            border: none;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--azul), transparent);
            margin: 25px 0;
        }

        /* Links do final da página */
        a {
            color: var(--azul-claro);
            text-decoration: none;
            font-weight: bold;
            transition: color 0.2s ease;
        }

        a:hover {
            color: var(--texto);
            text-decoration: underline;
        }

        p a {
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h1>Área do Administrador</h1>
    <p>Bem-vindo à administração do blog One Direction!</p>

    <?php if ($mensagem !== ''): ?>
        <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <hr>

    <!-- form de cadastro de noticia -->
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

    <!-- pesquisa por integrante -->
    <h2>Pesquisar integrante (tudo que há dele no site)</h2>

    <form method="GET" action="">
        <p>
            Nome do integrante:
            <input type="text" name="busca_integrante"
                   value="<?= htmlspecialchars($nomeIntegrante) ?>"
                   placeholder="ex.: Harry, Zayn, Niall, Louis, Liam" style="margin: 10px 0;">
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

    <p style="text-align: center;"><a href="../pages/noticias.php">Voltar para as notícias</a></p>
    <p style="text-align: center;"><a href="/projetointegrador/login/logout.php">Sair da conta</a></p>

</body>
</html>