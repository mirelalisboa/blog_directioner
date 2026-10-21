-- =====================================================
-- database/usuarios.sql — TABELA DE USUÁRIOS + ADMIN PADRÃO
-- O QUE FAZ: cria a tabela que sustenta login/cadastro
-- (login/login.php, login/cadastro.php), o perfil
-- (app/perfil.php) e a área do administrador (app/admin.php):
--   usuarios → id, nome, e-mail, senha (hash), tipo
--              ('admin' ou 'usuario' — o padrão é 'usuario')
--              e foto de perfil.
-- POR QUÊ: mesmo padrão de database/albuns.sql e
-- database/noticias.sql — o banco inteiro do site pode ser
-- recriado a partir dos scripts.
-- COMO USAR: rodar UMA VEZ no banco sitehtml (PostgreSQL
-- 192.168.10.62 — só acessível na rede local do projeto):
--   psql -h 192.168.10.62 -U sitehtml -d sitehtml -f database/usuarios.sql
-- Idempotente: reexecutar não duplica o admin nem altera
-- tabelas que já existem (IF NOT EXISTS em tudo).
-- =====================================================

CREATE TABLE IF NOT EXISTS usuarios (
    id             SERIAL PRIMARY KEY,
    name_sobrenome VARCHAR(120) NOT NULL,
    email          VARCHAR(160) NOT NULL,
    senha          VARCHAR(255) NOT NULL,
    tipo           VARCHAR(20)  DEFAULT 'usuario',
    foto_perfil    VARCHAR(255)
);

-- =====================================================
-- CADASTRO (SEED) — conta de administração padrão do site:
--   e-mail: mirela.r@edu.senai.br
--   senha:  1234567  (gravada como HASH bcrypt gerado por
--   password_hash(), igual ao login/cadastro do site)
--   tipo:   'admin' → libera app/admin.php (verificado por
--           tipo='admin' em app/admin.php e app/admin_login.php)
-- Só cadastra quando NÃO existe ainda um usuário com esse
-- e-mail; se existir, nada muda (nem senha nem tipo).
-- =====================================================

INSERT INTO usuarios (name_sobrenome, email, senha, tipo)
SELECT v.name_sobrenome, v.email, v.senha, v.tipo
FROM (VALUES
    ('Administrador',
     'mirela.r@edu.senai.br',
     '$2y$12$1XCi08jrGpFOJz6wVfX2DeBuZB0sRy8MyRD6WtNq6B94pvL8fgwTm',
     'admin')
) AS v(name_sobrenome, email, senha, tipo)
WHERE NOT EXISTS (
    SELECT 1 FROM usuarios WHERE email = 'mirela.r@edu.senai.br'
);
