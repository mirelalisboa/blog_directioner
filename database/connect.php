<?php

// dados do banco da escola (postgres)
$host = "192.168.10.62";
$dbname = "sitehtml";
$user = "sitehtml";
$pass = "1234";

// tenta criar a conexao com o banco (pdo)
try{
    $conexao = new PDO(
        "pgsql:host=$host;dbname=$dbname",
        $user,
        $pass
    );
    return $conexao;

} catch (PDOException $e){
    // se falhar, mostra o erro na tela
    echo "Erro: ". $e->getMessage();

}
?>