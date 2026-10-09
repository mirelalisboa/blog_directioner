
<?php

require_once __DIR__ . '/../database/connect.php';

// FUNÇÃO PARA CADASTRAR USUÁRIO

function cadastra_user($conexao, $nome, $email, $senha)
{
    // Protege a senha antes de salvar no banco.
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (name_sobrenome, email, senha)
            VALUES (:nome, :email, :senha)";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        // Retorna falso para a página tratar o erro.
        return false;
    }
}


// FUNÇÃO PARA CONSULTAR USUÁRIO PELO EMAIL


function consulta_user($conexao, $email)
{
    $sql = "SELECT id, name_sobrenome, email, senha
            FROM usuarios
            WHERE email = :email";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retorna os dados ou falso se não encontrar.
        return $usuario ? $usuario : false;

    } catch (PDOException $e) {
        return false;
    }
}


// FUNÇÃO PARA CONSULTAR O PERFIL DO USUÁRIO


function consultar_perfil($conexao, $id)
{
    $sql = "SELECT id, name_sobrenome, email
            FROM usuarios
            WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        // Retorna os dados para a página de perfil.
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ? $usuario : false;

    } catch (PDOException $e) {
        return false;
    }
}

// FUNÇÃO PARA ATUALIZAR NOME E EMAIL DO USUÁRIO


function atualizar_user($conexao, $id, $nome, $email)
{
    $sql = "UPDATE usuarios
            SET name_sobrenome = :nome,
                email = :email
            WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);

        return $stmt->execute();

    } catch (PDOException $e) {
        return false;
    }
}

// FUNÇÃO PARA ALTERAR A SENHA DO USUÁRIO


function atualizar_senha($conexao, $id, $senha)
{
    // Nunca salva a nova senha em texto puro.
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "UPDATE usuarios
            SET senha = :senha
            WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":senha", $senha_hash);

        return $stmt->execute();

    } catch (PDOException $e) {
        return false;
    }
}


// FUNÇÃO PARA EXCLUIR USUÁRIO


function excluir_user($conexao, $id)
{
    $sql = "DELETE FROM usuarios WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();

    } catch (PDOException $e) {
        return false;
    }
}

?>