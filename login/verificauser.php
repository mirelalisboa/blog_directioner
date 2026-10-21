<!-- =====================================================
     login/verificauser.php — GUARDA DE SESSÃO (2 de 2)
     O QUE FAZ: protege páginas que exigem login e redireciona
     para a HOME quando a sessão está vazia.
     POR QUÊ: segundo guarda do projeto (o outro é o
     includes/verificalogin.php). História: antes checava uma
     chave que ninguém gravava ('id_usuario'), causando o loop
     de login; hoje usa $_SESSION['id'], a chave correta que
     login_user() grava no includes/functions.php.
     ===================================================== -->
<?php
// Garante sessão ativa antes de ler $_SESSION.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sem usuário conectado (sem 'id') → volta para a início do site.
// O ../ relaxa porque todas as páginas que incluem este arquivo
// ficam em subpastas (app/, pages/, login/) → raiz do projeto.
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
