-- =====================================================
-- database/noticias.sql — TABELAS E CADASTRO DAS NOTÍCIAS
-- O QUE FAZ: cria as tabelas que sustentam o blog de
-- pages/noticias.php e a área do administrador (app/admin.php):
--   noticias   → uma notícia por registro (título, texto,
--                foto, link externo);
--   comentarios → comentários por notícia;
--   curtidas   → 1 curtida por par notícia+usuário;
--   favoritos  → 1 favorito por par notícia+usuário.
-- POR QUÊ: mesmo padrão de database/albuns.sql — o banco
-- inteiro do site pode ser recriado a partir dos scripts.
-- COMO USAR: rodar UMA VEZ no banco sitehtml (PostgreSQL
-- 192.168.10.62 — só acessível na rede local do projeto):
--   psql -h 192.168.10.62 -U sitehtml -d sitehtml -f database/noticias.sql
-- Idempotente: reexecutar não duplica as notícias nem altera
-- as tabelas que já existem (IF NOT EXISTS em tudo).
-- =====================================================

-- Notícia do blog (mesmos campos da listagem em
-- includes/functions.php → listar_noticias(): id, titulo,
-- noticia, foto, link, data_publicacao).
CREATE TABLE IF NOT EXISTS noticias (
    id              SERIAL PRIMARY KEY,
    titulo          VARCHAR(200),
    noticia         TEXT,
    foto            VARCHAR(255),
    link            VARCHAR(255),
    link_usuario    VARCHAR(500),
    comentarios     TEXT,
    data_publicacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Comentários de uma notícia (mesma ideia da tabela
-- album_comentarios dos álbuns).
CREATE TABLE IF NOT EXISTS comentarios (
    id         BIGSERIAL PRIMARY KEY,
    noticia_id BIGINT NOT NULL REFERENCES noticias (id) ON DELETE CASCADE,
    comentario TEXT    NOT NULL
);

-- Curtidas: par único notícia+usuário (mesma ideia da tabela
-- album_curtidas dos álbuns). Usuário vem de usuarios(id)
-- (script database/usuarios.sql).
CREATE TABLE IF NOT EXISTS curtidas (
    id         BIGSERIAL PRIMARY KEY,
    noticia_id BIGINT NOT NULL REFERENCES noticias (id) ON DELETE CASCADE,
    usuario_id BIGINT NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE,
    criado_em  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT curtida_unica UNIQUE (noticia_id, usuario_id)
);

-- Favoritos: par único notícia+usuário (mesma ideia da tabela
-- album_favoritos dos álbuns).
CREATE TABLE IF NOT EXISTS favoritos (
    id         BIGSERIAL PRIMARY KEY,
    noticia_id BIGINT NOT NULL REFERENCES noticias (id) ON DELETE CASCADE,
    usuario_id BIGINT NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE,
    criado_em  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT favorito_unico UNIQUE (noticia_id, usuario_id)
);

-- =====================================================
-- CADASTRO (SEED) — as 5 notícias que estavam no banco.
-- Só cadastra quando noticias ainda está vazia: em um banco
-- novo cria o acervo do blog; no banco vivo nada muda.
-- =====================================================

INSERT INTO noticias (titulo, noticia, foto, link, link_usuario, comentarios)
SELECT v.titulo, v.noticia, v.foto, v.link, v.link_usuario, v.comentarios
FROM (VALUES
    ('Tudo sobre One Direction (g1)',
     'Acervo de notícias do One Direction no g1: as últimas notícias e reportagens sobre a banda reunidas em um só lugar.',
     '',
     'https://g1.globo.com/tudo-sobre/one-direction/',
     '',
     ''),
    ('One Direction completa 16 anos (CNN Brasil)',
     'A banda completa 16 anos nesta quinta-feira (23): relembre a trajetória de Harry, Liam, Louis, Niall e Zayn.',
     '',
     'https://www.cnnbrasil.com.br/pop/musica/one-direction-banda-completa-16-anos-nesta-quinta-feira-23/',
     '',
     ''),
    ('Disco solo do Zayn tem gritos de liberdade e R&B moderninho (g1)',
     'Primeiro álbum em carreira solo do Zayn Malik rendeu críticas elogiosas após a saída do grupo em 2015.',
     '',
     'https://g1.globo.com/musica/noticia/2016/04/zayn-malik-disco-solo-tem-gritos-de-liberdade-indiretas-e-rb-moderninho.html',
     '',
     ''),
    ('Primeiro álbum sem Zayn chega em novembro (g1, 2015)',
     'O One Direction anunciou seu primeiro álbum de estúdio sem Zayn Malik: "Made In The A.M.".',
     '',
     'https://g1.globo.com/musica/noticia/2015/09/one-direction-lancara-primeiro-album-sem-zayn-malik-em-novembro.html',
     '',
     ''),
    ('Zayn anuncia seu primeiro show solo.',
     'depois de anos, zayn anuncia seu primeiro show solo, em solo brasileiro.',
     '',
     'https://veja.abril.com.br/cultura/zayn-ex-one-direction-anuncia-primeiro-show-solo-no-brasil/',
     '',
     '')
) AS v(titulo, noticia, foto, link, link_usuario, comentarios)
WHERE NOT EXISTS (SELECT 1 FROM noticias);
