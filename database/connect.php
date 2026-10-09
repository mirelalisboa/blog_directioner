<?php 
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