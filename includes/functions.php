
<?php

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
        header('Location: ../login.php');
        exit();
    }
}

?>

