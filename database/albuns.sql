-- =====================================================
-- database/albuns.sql — TABELAS E CADASTRO DOS ÁLBUNS
-- O QUE FAZ: cria as tabelas que sustentam os cards
-- interativos de pages/albuns.php (tipo 'banda') e
-- pages/albunssolos.php (tipo 'solo'):
--   albuns            → um registro por card (título, texto
--                       e link "Ouvir no YouTube");
--   album_comentarios → comentários por álbum;
--   album_curtidas    → 1 curtida por par álbum+usuário;
--   album_favoritos   → 1 favorito por par álbum+usuário.
-- POR QUÊ: pedido do dono do site — curtir/favoritar/comentar
-- álbuns no mesmo formato das notícias. Os textos que antes
-- ficavam hardcoded nas páginas passam a vir daqui.
-- COMO USAR: rodar UMA VEZ no banco sitehtml (PostgreSQL
-- 192.168.10.62 — só acessível na rede local do projeto):
--   psql -h 192.168.10.62 -U sitehtml -d sitehtml -f database/albuns.sql
-- Idempotente: reexecutar não duplica os álbuns.
-- =====================================================

CREATE TABLE IF NOT EXISTS albuns (
    id        SERIAL PRIMARY KEY,
    tipo      VARCHAR(10)  NOT NULL CHECK (tipo IN ('banda', 'solo')),
    titulo    VARCHAR(200) NOT NULL,
    descricao TEXT         NOT NULL,
    youtube   VARCHAR(300) NOT NULL,
    ordem     INTEGER      NOT NULL DEFAULT 0
);

-- Comentários de um card de álbum (mesma ideia da tabela
-- comentarios das notícias).
CREATE TABLE IF NOT EXISTS album_comentarios (
    id         SERIAL PRIMARY KEY,
    album_id   INTEGER NOT NULL REFERENCES albuns (id) ON DELETE CASCADE,
    comentario TEXT    NOT NULL
);

-- Curtidas: par único álbum+usuário (mesma ideia da tabela
-- curtidas das notícias). Usuário vem de usuarios(id).
CREATE TABLE IF NOT EXISTS album_curtidas (
    id         SERIAL PRIMARY KEY,
    album_id   INTEGER NOT NULL REFERENCES albuns (id) ON DELETE CASCADE,
    usuario_id INTEGER NOT NULL,
    CONSTRAINT album_curtidas_par_unico UNIQUE (album_id, usuario_id)
);

-- Favoritos: par único álbum+usuário (mesma ideia da tabela
-- favoritos das notícias).
CREATE TABLE IF NOT EXISTS album_favoritos (
    id         SERIAL PRIMARY KEY,
    album_id   INTEGER NOT NULL REFERENCES albuns (id) ON DELETE CASCADE,
    usuario_id INTEGER NOT NULL,
    CONSTRAINT album_favoritos_par_unico UNIQUE (album_id, usuario_id)
);

-- =====================================================
-- CADASTRO (SEED) — mesmos textos/links que antes ficavam
-- em pages/albuns.php e pages/albunssolos.php.
-- 'solo' = UM CARD POR INTEGRANTE (pedido do dono do site),
-- igual aos blocos antigos da página de álbuns solo.
-- Só cadastra quando albuns ainda está vazia.
-- =====================================================

INSERT INTO albuns (tipo, titulo, descricao, youtube, ordem)
SELECT v.tipo, v.titulo, v.descricao, v.youtube, v.ordem
FROM (VALUES
    ('banda', 'Up All Night',
     'Primeiro álbum de estúdio do One Direction, lançado em 2011. Foi o álbum que marcou o início da carreira musical do grupo e trouxe músicas que ficaram muito conhecidas, como What Makes You Beautiful e One Thing.',
     'https://www.youtube.com/results?search_query=One+Direction+Up+All+Night+album',
     1),
    ('banda', 'Take Me Home',
     'Segundo álbum do grupo, lançado em 2012. O álbum continuou o sucesso do One Direction e trouxe músicas como Live While We''re Young e Little Things.',
     'https://www.youtube.com/results?search_query=One+Direction+Take+Me+Home+album',
     2),
    ('banda', 'Midnight Memories',
     'Terceiro álbum do One Direction, lançado em 2013. O disco mostrou uma evolução no estilo musical do grupo e trouxe músicas como Best Song Ever, Story of My Life e Midnight Memories.',
     'https://www.youtube.com/results?search_query=One+Direction+Midnight+Memories+album',
     3),
    ('banda', 'FOUR',
     'Quarto álbum do grupo, lançado em 2014. FOUR trouxe uma sonoridade mais madura e músicas como Steal My Girl, Night Changes e Fireproof.',
     'https://www.youtube.com/results?search_query=One+Direction+FOUR+album',
     4),
    ('banda', 'Made In The A.M.',
     'Quinto e último álbum de estúdio do One Direction, lançado em 2015. O álbum inclui músicas como Drag Me Down, Perfect e History.',
     'https://www.youtube.com/results?search_query=One+Direction+Made+In+The+AM+album',
     5),
    ('solo', 'Harry Styles',
     'Harry Styles (2017), Fine Line (2019) e Harry''s House (2022) Kiss All the Time. Disco, Occasionally (2026). Após a pausa do grupo, Harry lançou carreira solo de sucesso, com destaque para "Sign of the Times", "Watermelon Sugar" e "As It Was".',
     'https://www.youtube.com/results?search_query=Harry+Styles+albums',
     1),
    ('solo', 'Zayn Malik',
     'Mind of Mine (2016), Icarus Falls (2018) e Nobody Is Listening (2021) Konnnacol (2026). Zayn foi o primeiro a sair do grupo, em 2015, e estreou em carreira solo com o hit "Pillowtalk", seguindo com um estilo mais R&B alternativo.',
     'https://www.youtube.com/results?search_query=Zayn+albums',
     2),
    ('solo', 'Niall Horan',
     'Flicker (2017), Heartbreak Weather (2020) e The Show (2023) Dinner Party (2026). Niall seguiu no estilo pop/folk, com sucessos como "Slow Hands", "Nice To Meet Ya" e "Heaven".',
     'https://www.youtube.com/results?search_query=Niall+Horan+albums',
     3),
    ('solo', 'Louis Tomlinson',
     'Walls (2020) e Faith in the Future (2022) How Did I Get Here? (2026). Louis apostou em um som mais indie rock, com músicas como "Walls", "Kill My Mind" e "Out of My System".',
     'https://www.youtube.com/results?search_query=Louis+Tomlinson+albums',
     4),
    ('solo', 'Liam Payne',
     'LP1 (2019). Liam lançou seu álbum único em carreira solo, com destaque para "Strip That Down", "Familiar" e "For You", Feat com Rita Ora.',
     'https://www.youtube.com/results?search_query=Liam+Payne+albums',
     5)
) AS v(tipo, titulo, descricao, youtube, ordem)
WHERE NOT EXISTS (SELECT 1 FROM albuns);
