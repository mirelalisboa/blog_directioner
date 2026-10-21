<!-- =====================================================
     login/logout.php — ENCERRA A SESSÃO
     O QUE FAZ: desloga a pessoa e manda para a página inicial.
     POR QUÊ: é o destino do link "Sair da conta" (ex.: área do
     admin em app/admin.php).
     FLUXO: guarda de login → limpa $_SESSION → destrói a sessão
     → redireciona para index.php (página inicial do site).
     ===================================================== -->
<?php
// 1) Só quem está logado precisa sair; caso contrário, o guarda
//    redireciona (evita logout de sessão vazia).
require_once __DIR__ . '/../login/verificauser.php';

// Garante sessão ativa para poder destruí-la.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Limpa TUDO que a sessão guardava (id, usuario, email...).
$_SESSION = [];

// 3) Encerra a sessão no servidor (remove dados do PHP).
session_destroy();

// 4) Volta para a página inicial do site (index.php), onde o
//    usuário já cai deslogado. Caminho absoluto com o prefixo
//    virtual do site (padrão dos demais redirects).
header("Location: /projetointegrador/index.php");
exit();
