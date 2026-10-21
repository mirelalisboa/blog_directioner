<!-- =====================================================
     app/perfil.php — MEU PERFIL
     O QUE FAZ: mostra os dados da conta logada (nome, e-mail,
     foto) e os atalhos para atualizar/excluir a conta, além dos
     botões de volta para as outras páginas do site.
     POR QUÊ: centralizador da conta do usuário dentro de app/.
     DEPENDÊNCIAS: verificalogin.php + verificauser.php (guardas)
     e $conexao (vinda por functions.php).
     ===================================================== -->
<?php

// Sessão: precisamos do id (guardas) e do e-mail (busca no perfil).
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1º guarda: exige login (manda para a home se vazio).
require_once __DIR__ . '/../includes/verificalogin.php';
// functions.php: funções + $conexao do banco.
require_once __DIR__ . '/../includes/functions.php';
// 2º guarda: reforço de sessão que também redireciona.
require_once __DIR__ . '/../login/verificauser.php';

// Sem e-mail na sessão não há como localizar o perfil.
// (a chave é gravada no login e no cadastro).
if (empty($_SESSION['email'])) {
    header('Location: /projetointegrador/index.php');
    exit();
}

// ------------------------------------------------------------------
// SELECT com WHERE: busca os dados pelo e-mail da sessão.
// foto_perfil é o NOME do arquivo gravado pelo app/update.php
// (quando existe, a imagem vive em /imagens/perfis/).
// ------------------------------------------------------------------
$sql = "SELECT name_sobrenome, email, foto_perfil
        FROM usuarios
        WHERE email = :email";

$stmt = $conexao->prepare($sql);
$stmt->execute([
    ':email' => $_SESSION['email']
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// E-mail da sessão não existe mais no banco? (conta apagada
// em outra aba, por exemplo) → mensagem e fim.
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
        /* Página limpa, fundo claro, cartão central */
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

        /* Moldura redonda da foto de perfil */
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

        /* Linhas de informação (nome / e-mail) */
        .informacao {
            margin: 15px 0;
        }

        /* Fileira de botões (atualizar/excluir + voltar) */
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

        /* Bloco das notícias favoritas do usuário */
        .favoritas {
            margin-top: 28px;
            text-align: left;
        }

        .favoritas h2 {
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .favoritas ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .favoritas li {
            border-top: 1px solid #eeeeee;
            padding: 10px 0;
        }

        .favoritas .titulo {
            font-weight: bold;
        }

        .favoritas .resumo {
            font-size: 0.9em;
            color: #555555;
            margin: 4px 0;
        }

        .favoritas small {
            color: #888888;
        }
    </style>
</head>

<body>

<div class="perfil">

    <h1>Meu perfil</h1>

    <!-- Foto de perfil: exibe a imagem enviada na atualização
         de conta (rawurlencode protege espaços/símbolos do nome
         do arquivo) ou o emoji padrão quando não tem foto. -->
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

    <!-- Dados da conta (htmlspecialchars evita injeção de HTML) -->
    <div class="informacao">
        <strong>Nome:</strong>
        <?php echo htmlspecialchars($usuario['name_sobrenome']); ?>
    </div>

    <div class="informacao">
        <strong>E-mail:</strong>
        <?php echo htmlspecialchars($usuario['email']); ?>
    </div>

    <!-- Ação da conta: mesma pasta (app/) -->
    <div class="botoes">
        <a href="update.php">Atualizar conta</a>
        <a href="delete.php">Excluir conta</a>
    </div>

    <!-- Voltar para as páginas do site (links absolutos) -->
    <div class="botoes" style="margin-top: 14px;">
        <a href="/projetointegrador/pages/noticias.php">Notícias</a>
        <a href="/projetointegrador/pages/albuns.php">Álbuns</a>
        <a href="/projetointegrador/pages/albunssolos.php">Álbuns solo</a>
        <a href="/projetointegrador/pages/integrantes.php">Integrantes</a>
        <a href="/projetointegrador/pages/linhadotempo.php">Linha do tempo</a>
    </div>

    <!-- Notícias que este usuário favoritou no blog:
         listar_favoritos() traz a notícia + contagem de curtidas -->
    <?php $favoritas = listar_favoritos($conexao, (int) $_SESSION['id']); ?>

    <div class="favoritas">
        <h2>&starf; Minhas notícias favoritas</h2>

        <?php if (empty($favoritas)): ?>
            <p style="color:#777777;">
                Você ainda não favoritou nenhuma notícia.
                Favoritadas por aqui aparecem quando você clica em
                "Favoritar" em uma notícia do blog.
            </p>
        <?php else: ?>
            <ul>
                <?php foreach ($favoritas as $f): ?>
                    <li>
                        <span class="titulo">
                            <?php echo htmlspecialchars($f['titulo'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>

                        <p class="resumo">
                            <?php echo nl2br(htmlspecialchars(mb_substr($f['noticia'], 0, 140), ENT_QUOTES, 'UTF-8')); ?>…
                        </p>

                        <small>
                            &hearts; <?php echo (int) $f['curtidas']; ?> curtida(s)
                        </small>

                        &middot;

                        <!-- Abre a notícia (e as ações) no blog -->
                        <a href="/projetointegrador/pages/noticias.php#noticia-<?php echo (int) $f['id']; ?>">Ver no blog</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Álbuns que este usuário favoritou (banda e solos):
         listar_favoritos_albuns() traz id, título, tipo e a
         contagem de curtidas de cada um. O tipo decide o link
         (banda = albuns.php, solo = albunssolos.php); a âncora
         #album-ID abre o card certo. -->
    <?php $favoritos_albuns = listar_favoritos_albuns($conexao, (int) $_SESSION['id']); ?>

    <div class="favoritas">
        <h2>&starf; Meus álbuns favoritos</h2>

        <?php if (empty($favoritos_albuns)): ?>
            <p style="color:#777777;">
                Você ainda não favoritou nenhum álbum.
                Favoritados por aqui aparecem quando você clica em
                "Favoritar" em um card das páginas Álbuns ou Álbuns solo.
            </p>
        <?php else: ?>
            <ul>
                <?php foreach ($favoritos_albuns as $albun): ?>
                    <li>
                        <span class="titulo">
                            <?php echo htmlspecialchars($albun['titulo'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>

                        <small>
                            <?php echo $albun['tipo'] === 'solo' ? 'Álbum solo' : 'Álbum da banda'; ?>
                        </small>

                        &middot;

                        <small>
                            &hearts; <?php echo (int) $albun['curtidas']; ?> curtida(s)
                        </small>

                        &middot;

                        <!-- Abre o álbum (e as ações) na página certa -->
                        <a href="<?php echo $albun['tipo'] === 'solo'
                            ? '/projetointegrador/pages/albunssolos.php'
                            : '/projetointegrador/pages/albuns.php'; ?>#album-<?php echo (int) $albun['id']; ?>">
                            Ver na página
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
