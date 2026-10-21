<?php
// =====================================================
// includes/functions.php — BIBLIOTECA CENTRAL DO SITE
// O QUE FAZ: concentra TODAS as funções de dados que o
// site usa (CRUD de usuários e do blog, login/sessão,
// guardas, pesquisa/exclusão para o admin).
// POR QUÊ: qualquer página que precisar de banco obtém
// $conexao por aqui (o require abaixo já abre a conexão) —
// é por isso que as páginas incluem functions.php e não
// database/connect.php diretamente.
// Padrões adotados em todas as funções:
//  - prepared statements (bindValue/execução com array);
//  - catch PDOException → log (error_log) e false/[] ;
//  - validação dos parâmetros antes de qualquer SQL.
// =====================================================

require_once __DIR__ . '/../database/connect.php';


// ======================================================
// CREATE: CADASTRAR USUÁRIO
// ======================================================


function cadastra_user($conexao, $nome, $email, $senha)
{
    // Verifica se os campos estão preenchidos
    if (
        trim($nome) === '' ||
        trim($email) === '' ||
        $senha === ''
    ) {
        return false;
    }

    // Verifica se o e-mail é válido
    if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Protege a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Comando para inserir o usuário
    $sql = "INSERT INTO usuarios
            (name_sobrenome, email, senha)
            VALUES (:nome, :email, :senha)";

    try {
        $stmt = $conexao->prepare($sql);

        $stmt->bindValue(':nome', trim($nome));
        $stmt->bindValue(':email', trim($email));
        $stmt->bindValue(':senha', $senha_hash);

        // Executa o cadastro
        return $stmt->execute();

    } catch (PDOException $erro) {
        // Mostra o erro durante o desenvolvimento
        error_log("Erro ao cadastrar usuário: " . $erro->getMessage());

        return false;
    }
}





// ======================================================
// SELECT COM WHERE: BUSCAR USUÁRIO PELO EMAIL
// Usado no login.
// ======================================================

function consulta_user($conexao, $email)
{
    $sql = "SELECT id, name_sobrenome, email, senha
            FROM usuarios
            WHERE email = :email";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":email", trim($email));
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ? $usuario : false;

    } catch (PDOException $e) {
        return false;
    }
}


// ======================================================
// SELECT COM WHERE: BUSCAR DADOS PELO EMAIL
// Para preencher automaticamente os campos.
// ======================================================

function consultar_por_email($conexao, $email)
{
    $sql = "SELECT id, name_sobrenome, email
            FROM usuarios
            WHERE email = :email";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":email", trim($email));
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ? $usuario : false;

    } catch (PDOException $e) {
        return false;
    }
}


// ======================================================
// SELECT COM WHERE: CONSULTAR PERFIL PELO ID
// Busca os dados do usuário conectado.
// ======================================================

function consultar_perfil($conexao, $id)
{
    $sql = "SELECT id, name_sobrenome, email
            FROM usuarios
            WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ? $usuario : false;

    } catch (PDOException $e) {
        return false;
    }
}


// ======================================================
// SELECT: LISTAR TODOS OS USUÁRIOS
// Para relatórios da área administrativa.
// ======================================================

function selecionar_usuarios($conexao)
{
    $sql = "SELECT id, name_sobrenome, email
            FROM usuarios
            ORDER BY id ASC";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return false;
    }
}


// ======================================================
// UPDATE: ATUALIZAR NOME E EMAIL
// ======================================================

function atualizar_user($conexao, $id, $nome, $email)
{
    if (empty(trim($nome)) || empty(trim($email))) {
        return false;
    }

    $sql = "UPDATE usuarios
            SET name_sobrenome = :nome,
                email = :email
            WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":nome", trim($nome));
        $stmt->bindValue(":email", trim($email));

        return $stmt->execute();

    } catch (PDOException $e) {
        return false;
    }
}


// ======================================================
// UPDATE: ALTERAR SENHA
// ======================================================

function atualizar_senha($conexao, $id, $senha)
{
    if ($senha === '') {
        return false;
    }

    // Salva a senha usando hash.
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "UPDATE usuarios
            SET senha = :senha
            WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":senha", $senha_hash);

        return $stmt->execute();

    } catch (PDOException $e) {
        return false;
    }
}


// ======================================================
// DELETE: EXCLUIR USUÁRIO PELO EMAIL
// ======================================================

// Função para excluir um usuário pelo e-mail.
function excluir_user($conexao, $email)
{
    // Verifica se o e-mail foi informado.
    if (empty($email)) {
        return false;
    }

    // Prepara a consulta para excluir o usuário.
    $sql = "DELETE FROM usuarios WHERE email = :email";

    try {
        $stmt = $conexao->prepare($sql);

        // Associa o e-mail à consulta.
        $stmt->bindValue(':email', trim($email));

        // Executa a exclusão.
        $stmt->execute();

        // Retorna true se algum usuário foi excluído.
        return $stmt->rowCount() > 0;

    } catch (PDOException $e) {
        error_log("Erro ao excluir usuário: " . $e->getMessage());
        return false;
    }
}



// ======================================================
// FUNÇÃO PARA CRIAR UMA CONTA NOVA
// Separada da função de login.
// ======================================================

function criar_conta($conexao, $nome, $email, $senha)
{
    // Verifica se os campos foram preenchidos.
    if (
        trim($nome) === '' ||
        trim($email) === '' ||
        $senha === ''
    ) {
        return false;
    }

    // Verifica se o email já está cadastrado.
    $usuario_existente = consulta_user($conexao, $email);

    if ($usuario_existente) {
        return false;
    }

    // Salva os dados usando a função de cadastro existente.
    return cadastra_user($conexao, $nome, $email, $senha);
}


// ======================================================
// FUNÇÃO PARA FAZER LOGIN
// Busca o usuário e verifica a senha.
// ======================================================

function login_user($conexao, $email, $senha)
{
    // Procura o usuário pelo email.
    $usuario = consulta_user($conexao, $email);

    // Verifica se o usuário existe e se a senha está correta.
    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        return false;
    }

    // Inicia a sessão caso ainda não esteja iniciada.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Renova o identificador da sessão.
    session_regenerate_id(true);

    // Guarda os dados necessários do usuário na sessão.
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['usuario'] = $usuario['name_sobrenome'];
    $_SESSION['email'] = $usuario['email'];

    return true;
}


function verifica_user()
{
    // Inicia a sessão, se necessário
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica se o usuário está logado
    if (!isset($_SESSION['id'])) {
        header('Location: /projetointegrador/login/cadastro.php');
        exit();
    }
}


// ======================================================
// CREATE: ADICIONAR NOTÍCIA (blog)
// Usado por pages/noticias.php.
// ======================================================

function cadastra_noticia($conexao, $titulo, $noticia, $foto, $link)
{
    // Título e texto são obrigatórios.
    if (trim($titulo) === '' || trim($noticia) === '') {
        return false;
    }

    $sql = "INSERT INTO noticias (titulo, noticia, foto, link)
            VALUES (:titulo, :noticia, :foto, :link)";

    try {
        $stmt = $conexao->prepare($sql);
        return $stmt->execute([
            ':titulo'  => trim($titulo),
            ':noticia' => trim($noticia),
            ':foto'    => trim($foto),
            ':link'    => trim($link)
        ]);
    } catch (PDOException $erro) {
        error_log("Erro ao cadastrar notícia: " . $erro->getMessage());
        return false;
    }
}


// ======================================================
// CREATE: COMENTÁRIO EM UMA NOTÍCIA
// Usado por pages/noticias.php.
// ======================================================

function adiciona_comentario($conexao, $noticia_id, $comentario)
{
    $noticia_id = (int) $noticia_id;

    // Comentário vazio ou notícia inválida.
    if ($noticia_id <= 0 || trim($comentario) === '') {
        return false;
    }

    // Verifica se a notícia existe antes de comentar.
    $verifica = $conexao->prepare(
        "SELECT id FROM noticias WHERE id = :id"
    );
    $verifica->execute([':id' => $noticia_id]);

    if (!$verifica->fetch()) {
        return false;
    }

    $sql = "INSERT INTO comentarios (noticia_id, comentario)
            VALUES (:noticia_id, :comentario)";

    try {
        $stmt = $conexao->prepare($sql);
        return $stmt->execute([
            ':noticia_id' => $noticia_id,
            ':comentario' => trim($comentario)
        ]);
    } catch (PDOException $erro) {
        error_log("Erro ao adicionar comentário: " . $erro->getMessage());
        return false;
    }
}


// ======================================================
// SELECT: LISTAR NOTÍCIAS (mais recentes primeiro)
// ======================================================

function listar_noticias($conexao)
{
    try {
        $stmt = $conexao->query(
            "SELECT id, titulo, noticia, foto, link, data_publicacao
             FROM noticias ORDER BY id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar notícias: " . $e->getMessage());
        return [];
    }
}


// ======================================================
// SELECT: LISTAR COMENTÁRIOS DE UMA NOTÍCIA
// Retorna [] se não houver comentários.
// ======================================================

function listar_comentarios($conexao, $noticia_id)
{
    $sql = "SELECT id, comentario
            FROM comentarios
            WHERE noticia_id = :noticia_id
            ORDER BY id ASC";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':noticia_id', (int) $noticia_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar comentários: " . $e->getMessage());
        return [];
    }
}


// ======================================================
// ADMIN: PESQUISAR NOTÍCIAS (SELECT com WHERE)
//
// Busca por trecho do título; se o termo for numérico,
// localiza pelo id exato.
// ======================================================

function pesquisar_noticias($conexao, $termo)
{
    $termo = trim($termo);

    // Sem termo, sem pesquisa.
    if ($termo === '') {
        return [];
    }

    try {
        // Termo numérico pesquisa pelo id exato.
        if (ctype_digit($termo)) {
            $sql = "SELECT id, titulo, noticia, foto, link
                    FROM noticias
                    WHERE id = :id";

            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(':id', (int) $termo, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Senão, pesquisa por trecho do título (sem diferenciar maiúsculas).
        $sql = "SELECT id, titulo, noticia, foto, link
                FROM noticias
                WHERE titulo ILIKE :termo
                ORDER BY id DESC";

        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':termo', '%' . $termo . '%');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao pesquisar notícias: " . $e->getMessage());
        return [];
    }
}


// ======================================================
// ADMIN: EXCLUIR NOTÍCIA PELO ID
// Os comentários vinculados são removidos pelo banco
// (ON DELETE CASCADE).
// ======================================================

function excluir_noticia($conexao, $id)
{
    $id = (int) $id;

    if ($id <= 0) {
        return false;
    }

    $sql = "DELETE FROM noticias WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        error_log("Erro ao excluir notícia: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// LIKE: CURTIR / DESCURTIR UMA NOTÍCIA (toggle)
//
// Tabela curtidas tem par único (noticia_id, usuario_id),
// então cada usuário curte cada notícia uma só vez.
// Devolve true = curtiu, false = descurtiu (ou erro logado).
// ======================================================

function alternar_curtida($conexao, $noticia_id, $usuario_id)
{
    $noticia_id = (int) $noticia_id;
    $usuario_id = (int) $usuario_id;

    if ($noticia_id <= 0 || $usuario_id <= 0) {
        return false;
    }

    try {
        // Já curtiu? → remove a curtida (descurtiu).
        $ver = $conexao->prepare(
            "SELECT id FROM curtidas
             WHERE noticia_id = :n AND usuario_id = :u"
        );
        $ver->execute([':n' => $noticia_id, ':u' => $usuario_id]);

        if ($ver->fetch()) {
            $del = $conexao->prepare(
                "DELETE FROM curtidas
                 WHERE noticia_id = :n AND usuario_id = :u"
            );
            $del->execute([':n' => $noticia_id, ':u' => $usuario_id]);

            return false;
        }

        // Não tinha curtida → grava a nova.
        $ins = $conexao->prepare(
            "INSERT INTO curtidas (noticia_id, usuario_id)
             VALUES (:n, :u)"
        );
        $ins->execute([':n' => $noticia_id, ':u' => $usuario_id]);

        return true;
    } catch (PDOException $e) {
        error_log("Erro ao curtir notícia: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// LIKE: TOTAL DE CURTIDAS DE UMA NOTÍCIA
// (para exibir o contador no card)
// ======================================================

function contar_curtidas($conexao, $noticia_id)
{
    $sql = "SELECT COUNT(*) FROM curtidas WHERE noticia_id = :n";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':n', (int) $noticia_id, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erro ao contar curtidas: " . $e->getMessage());
        return 0;
    }
}


// ======================================================
// LIKE: ESTE USUÁRIO JÁ CURTIU ESTA NOTÍCIA?
// (pinta o botão no estado "curtido")
// ======================================================

function usuario_curtiu($conexao, $noticia_id, $usuario_id)
{
    $sql = "SELECT id FROM curtidas
            WHERE noticia_id = :n AND usuario_id = :u";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':n' => (int) $noticia_id,
            ':u' => (int) $usuario_id
        ]);

        return (bool) $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erro ao verificar curtida: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// FAVORITO: FAVORITAR / REMOVER FAVORITO (toggle)
// Mesma lógica do toggle de curtidas, na tabela favoritos.
// ======================================================

function alternar_favorito($conexao, $noticia_id, $usuario_id)
{
    $noticia_id = (int) $noticia_id;
    $usuario_id = (int) $usuario_id;

    if ($noticia_id <= 0 || $usuario_id <= 0) {
        return false;
    }

    try {
        // Já favoritou? → remove (desfavorita).
        $ver = $conexao->prepare(
            "SELECT id FROM favoritos
             WHERE noticia_id = :n AND usuario_id = :u"
        );
        $ver->execute([':n' => $noticia_id, ':u' => $usuario_id]);

        if ($ver->fetch()) {
            $del = $conexao->prepare(
                "DELETE FROM favoritos
                 WHERE noticia_id = :n AND usuario_id = :u"
            );
            $del->execute([':n' => $noticia_id, ':u' => $usuario_id]);

            return false;
        }

        // Não tinha → grava o favorito.
        $ins = $conexao->prepare(
            "INSERT INTO favoritos (noticia_id, usuario_id)
             VALUES (:n, :u)"
        );
        $ins->execute([':n' => $noticia_id, ':u' => $usuario_id]);

        return true;
    } catch (PDOException $e) {
        error_log("Erro ao favoritar notícia: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// FAVORITO: ESTE USUÁRIO FAVORITOU ESTA NOTÍCIA?
// ======================================================

function usuario_favoritou($conexao, $noticia_id, $usuario_id)
{
    $sql = "SELECT id FROM favoritos
            WHERE noticia_id = :n AND usuario_id = :u";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':n' => (int) $noticia_id,
            ':u' => (int) $usuario_id
        ]);

        return (bool) $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erro ao verificar favorito: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// FAVORITO: LISTAR AS NOTÍCIAS FAVORITAS DO USUÁRIO
// (para a seção "Notícias favoritas" do perfil)
// Devolve as notícias + total de curtidas de cada uma.
// ======================================================

function listar_favoritos($conexao, $usuario_id)
{
    // JOIN: favoritos liga à notícia real; o sub_select conta
    // as curtidas de cada notícia para exibir no perfil.
    $sql = "SELECT n.id, n.titulo, n.noticia, n.foto, n.link,
                   (SELECT COUNT(*) FROM curtidas c
                    WHERE c.noticia_id = n.id) AS curtidas
            FROM favoritos f
            JOIN noticias n ON n.id = f.noticia_id
            WHERE f.usuario_id = :usuario_id
            ORDER BY f.id DESC";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':usuario_id', (int) $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar favoritos: " . $e->getMessage());
        return [];
    }
}


// ======================================================
// ÁLBUNS: CARDS INTERATIVOS (curtir/favoritar/comentar)
// Mesma mecânica das funções de blog acima, agora ligada à
// tabela albuns (script database/albuns.sql cria as tabelas).
// O campo tipo decide a página do card:
//   'banda' → pages/albuns.php (um card por álbum do grupo)
//   'solo'  → pages/albunssolos.php (um card por integrante)
// ======================================================


// ======================================================
// SELECT: LISTAR OS ÁLBUNS DE UMA PÁGINA (banda ou solo)
// Ordem fixa do cadastro (coluna ordem, depois id).
// Retorna [] se não houver álbuns (ou erro logado).
// ======================================================

function listar_albuns($conexao, $tipo)
{
    // Só os dois valores válidos (evita consulta estranha).
    if ($tipo !== 'banda' && $tipo !== 'solo') {
        return [];
    }

    $sql = "SELECT id, titulo, descricao, youtube, ordem
            FROM albuns
            WHERE tipo = :tipo
            ORDER BY ordem ASC, id ASC";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar álbuns: " . $e->getMessage());
        return [];
    }
}


// ======================================================
// CREATE: COMENTÁRIO EM UM ÁLBUM
// Usado por pages/albuns.php e pages/albunssolos.php.
// ======================================================

function adiciona_comentario_album($conexao, $album_id, $comentario)
{
    $album_id = (int) $album_id;

    // Comentário vazio ou álbum inválido.
    if ($album_id <= 0 || trim($comentario) === '') {
        return false;
    }

    // Verifica se o álbum existe antes de comentar.
    $verifica = $conexao->prepare(
        "SELECT id FROM albuns WHERE id = :id"
    );
    $verifica->execute([':id' => $album_id]);

    if (!$verifica->fetch()) {
        return false;
    }

    $sql = "INSERT INTO album_comentarios (album_id, comentario)
            VALUES (:album_id, :comentario)";

    try {
        $stmt = $conexao->prepare($sql);
        return $stmt->execute([
            ':album_id'   => $album_id,
            ':comentario' => trim($comentario)
        ]);
    } catch (PDOException $erro) {
        error_log("Erro ao adicionar comentário de álbum: " . $erro->getMessage());
        return false;
    }
}


// ======================================================
// SELECT: LISTAR COMENTÁRIOS DE UM ÁLBUM
// Retorna [] se não houver comentários.
// ======================================================

function listar_comentarios_album($conexao, $album_id)
{
    $sql = "SELECT id, comentario
            FROM album_comentarios
            WHERE album_id = :album_id
            ORDER BY id ASC";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':album_id', (int) $album_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar comentários do álbum: " . $e->getMessage());
        return [];
    }
}


// ======================================================
// LIKE: CURTIR / DESCURTIR UM ÁLBUM (toggle)
// Tabela album_curtidas tem par único (album_id, usuario_id),
// então cada usuário curte cada álbum uma só vez.
// Devolve true = curtiu, false = descurtiu (ou erro logado).
// ======================================================

function alternar_curtida_album($conexao, $album_id, $usuario_id)
{
    $album_id = (int) $album_id;
    $usuario_id = (int) $usuario_id;

    if ($album_id <= 0 || $usuario_id <= 0) {
        return false;
    }

    try {
        // Já curtiu? → remove a curtida (descurtiu).
        $ver = $conexao->prepare(
            "SELECT id FROM album_curtidas
             WHERE album_id = :a AND usuario_id = :u"
        );
        $ver->execute([':a' => $album_id, ':u' => $usuario_id]);

        if ($ver->fetch()) {
            $del = $conexao->prepare(
                "DELETE FROM album_curtidas
                 WHERE album_id = :a AND usuario_id = :u"
            );
            $del->execute([':a' => $album_id, ':u' => $usuario_id]);

            return false;
        }

        // Não tinha curtida → grava a nova.
        $ins = $conexao->prepare(
            "INSERT INTO album_curtidas (album_id, usuario_id)
             VALUES (:a, :u)"
        );
        $ins->execute([':a' => $album_id, ':u' => $usuario_id]);

        return true;
    } catch (PDOException $e) {
        error_log("Erro ao curtir álbum: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// LIKE: TOTAL DE CURTIDAS DE UM ÁLBUM
// (para exibir o contador no card)
// ======================================================

function contar_curtidas_album($conexao, $album_id)
{
    $sql = "SELECT COUNT(*) FROM album_curtidas WHERE album_id = :a";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':a', (int) $album_id, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erro ao contar curtidas do álbum: " . $e->getMessage());
        return 0;
    }
}


// ======================================================
// LIKE: ESTE USUÁRIO JÁ CURTIU ESTE ÁLBUM?
// (pinta o botão no estado "curtido")
// ======================================================

function usuario_curtiu_album($conexao, $album_id, $usuario_id)
{
    $sql = "SELECT id FROM album_curtidas
            WHERE album_id = :a AND usuario_id = :u";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':a' => (int) $album_id,
            ':u' => (int) $usuario_id
        ]);

        return (bool) $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erro ao verificar curtida do álbum: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// FAVORITO: FAVORITAR / REMOVER FAVORITO DE ÁLBUM (toggle)
// Mesma lógica do toggle de curtidas, na tabela album_favoritos.
// ======================================================

function alternar_favorito_album($conexao, $album_id, $usuario_id)
{
    $album_id = (int) $album_id;
    $usuario_id = (int) $usuario_id;

    if ($album_id <= 0 || $usuario_id <= 0) {
        return false;
    }

    try {
        // Já favoritou? → remove (desfavorita).
        $ver = $conexao->prepare(
            "SELECT id FROM album_favoritos
             WHERE album_id = :a AND usuario_id = :u"
        );
        $ver->execute([':a' => $album_id, ':u' => $usuario_id]);

        if ($ver->fetch()) {
            $del = $conexao->prepare(
                "DELETE FROM album_favoritos
                 WHERE album_id = :a AND usuario_id = :u"
            );
            $del->execute([':a' => $album_id, ':u' => $usuario_id]);

            return false;
        }

        // Não tinha → grava o favorito.
        $ins = $conexao->prepare(
            "INSERT INTO album_favoritos (album_id, usuario_id)
             VALUES (:a, :u)"
        );
        $ins->execute([':a' => $album_id, ':u' => $usuario_id]);

        return true;
    } catch (PDOException $e) {
        error_log("Erro ao favoritar álbum: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// FAVORITO: ESTE USUÁRIO FAVORITOU ESTE ÁLBUM?
// ======================================================

function usuario_favoritou_album($conexao, $album_id, $usuario_id)
{
    $sql = "SELECT id FROM album_favoritos
            WHERE album_id = :a AND usuario_id = :u";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':a' => (int) $album_id,
            ':u' => (int) $usuario_id
        ]);

        return (bool) $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erro ao verificar favorito de álbum: " . $e->getMessage());
        return false;
    }
}


// ======================================================
// FAVORITO: LISTAR OS ÁLBUNS FAVORITOS DO USUÁRIO
// (para a seção "Meus álbuns favoritos" do perfil)
// Devolve o álbum (título, tipo) + total de curtidas de cada um.
// ======================================================

function listar_favoritos_albuns($conexao, $usuario_id)
{
    // JOIN: album_favoritos liga ao álbum real; o sub_select
    // conta as curtidas de cada álbum para exibir no perfil.
    $sql = "SELECT a.id, a.titulo, a.tipo,
                   (SELECT COUNT(*) FROM album_curtidas c
                    WHERE c.album_id = a.id) AS curtidas
            FROM album_favoritos f
            JOIN albuns a ON a.id = f.album_id
            WHERE f.usuario_id = :usuario_id
            ORDER BY f.id DESC";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':usuario_id', (int) $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar favoritos de álbuns: " . $e->getMessage());
        return [];
    }
}


// ======================================================
// ÁLBUNS: IMPRIME AS INTERAÇÕES DE UM BLOCO
// (curtir + favoritar + comentários), no mesmo molde do
// blog de notícias. Usado por pages/albuns.php e pages/
// albunssolos.php para não repetir o HTML 10 vezes: recebe
// o registro do álbum (vindo de listar_albuns) e o id do
// usuário logado. Sem registro (banco sem cadastro ainda)
// não imprime nada — a página fica como era.
// ======================================================

function interacoes_album($conexao, $album, $usuario_id)
{
    // Sem registro de álbum → nada a imprimir.
    if ($album === null || empty($album['id'])) {
        return;
    }

    $album_id = (int) $album['id'];

    // Contagem de curtidas + estado do usuário logado
    // (pinta os botões quando ele já curtiu/favoritou).
    $curtidas    = contar_curtidas_album($conexao, $album_id);
    $curtiu      = usuario_curtiu_album($conexao, $album_id, $usuario_id);
    $favoritou   = usuario_favoritou_album($conexao, $album_id, $usuario_id);
    $comentarios = listar_comentarios_album($conexao, $album_id);
    ?>

    <!-- Curtir + Favoritar: o POST recarrega a página
         já mostrando o novo estado -->
    <div class="acoes">
        <form method="POST" action="#">
            <input type="hidden" name="tipo" value="curtir">
            <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">

            <button type="submit" class="btn-curtir<?php echo $curtiu ? ' ativo' : ''; ?>">
                &hearts; Curtir (<?php echo $curtidas; ?>)
            </button>
        </form>

        <form method="POST" action="#">
            <input type="hidden" name="tipo" value="favoritar">
            <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">

            <button type="submit" class="btn-favoritar<?php echo $favoritou ? ' ativo' : ''; ?>">
                <?php echo $favoritou ? '&starf; Favoritado' : '&star; Favoritar'; ?>
            </button>
        </form>
    </div>

    <!-- Comentários recolhidos: abrem no clique; o
         contador mostra quantos existem -->
    <details class="comentarios">
        <summary>Comentários (<?php echo count($comentarios); ?>)</summary>

        <?php foreach ($comentarios as $c): ?>
            <p>&laquo;<?php echo nl2br(htmlspecialchars($c['comentario'], ENT_QUOTES, 'UTF-8')); ?>&raquo;</p>
        <?php endforeach; ?>
        <?php if (empty($comentarios)): ?>
            <p style="color:#8b8b8b;">Nenhum comentário ainda.</p>
        <?php endif; ?>

        <!-- Formulário de comentário: o campo hidden
             album_id identifica o álbum do bloco -->
        <form method="POST" action="#" class="form-comentario">
            <input type="hidden" name="tipo" value="comentario">
            <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">

            <textarea name="comentario" rows="2" placeholder="Escreva um comentário..." required></textarea>

            <input type="submit" value="Comentar">
        </form>
    </details>
    <?php
}

?>

