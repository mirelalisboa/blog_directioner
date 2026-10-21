<?php
// =====================================================
// database/connect.php — CONEXÃO COM O BANCO (PostgreSQL)
// O QUE FAZ: abre uma conexão PDO com o banco sitehtml.
// POR QUÊ: o site todo conversa com um banco PostgreSQL
// (DSN pgsql:) hospedado na rede local da escola
// (192.168.10.62), por isso funciona só lá.
// COMO É USADO: incluído por includes/functions.php, que
// deixa o objeto $conexao disponível para todas as páginas.
// OBS: o return existe para uso como $conexao = require ...;
// o padrão do projeto, porém, é receber $conexao indiretamente
// via functions.php.
// NENHUM OUTPUT ANTES DO <?php: qualquer eco/HTML aqui
// quebraria header()/session_start() nas páginas que fazem
// redirect logo após requisitar functions.php.
// =====================================================
$host = "192.168.10.62";
$dbname = "sitehtml";
$user = "sitehtml";
$pass = "1234";

try{
    $conexao = new PDO(
        "pgsql:host=$host;dbname=$dbname",
        $user,
        $pass
    );
    return $conexao;

} catch (PDOException $e){
    echo "Erro: ". $e->getMessage();

}
?>