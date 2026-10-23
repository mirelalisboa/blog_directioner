--
-- PostgreSQL database dump
--

\restrict vThe4bsRLjmdIxFvHidgvGwrADpFt8oRbKehlrFNGmjxWxTmCqweDtvgVQfBmVY

-- Dumped from database version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)
-- Dumped by pg_dump version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: album_comentarios; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.album_comentarios (
    id integer NOT NULL,
    album_id integer NOT NULL,
    comentario text NOT NULL
);


ALTER TABLE public.album_comentarios OWNER TO sitehtml;

--
-- Name: album_comentarios_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.album_comentarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.album_comentarios_id_seq OWNER TO sitehtml;

--
-- Name: album_comentarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.album_comentarios_id_seq OWNED BY public.album_comentarios.id;


--
-- Name: album_curtidas; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.album_curtidas (
    id integer NOT NULL,
    album_id integer NOT NULL,
    usuario_id integer NOT NULL
);


ALTER TABLE public.album_curtidas OWNER TO sitehtml;

--
-- Name: album_curtidas_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.album_curtidas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.album_curtidas_id_seq OWNER TO sitehtml;

--
-- Name: album_curtidas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.album_curtidas_id_seq OWNED BY public.album_curtidas.id;


--
-- Name: album_favoritos; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.album_favoritos (
    id integer NOT NULL,
    album_id integer NOT NULL,
    usuario_id integer NOT NULL
);


ALTER TABLE public.album_favoritos OWNER TO sitehtml;

--
-- Name: album_favoritos_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.album_favoritos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.album_favoritos_id_seq OWNER TO sitehtml;

--
-- Name: album_favoritos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.album_favoritos_id_seq OWNED BY public.album_favoritos.id;


--
-- Name: albuns; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.albuns (
    id integer NOT NULL,
    titulo character varying(255) NOT NULL,
    descricao text,
    tipo character varying(10) DEFAULT 'banda'::character varying NOT NULL,
    youtube character varying(300) DEFAULT ''::character varying NOT NULL,
    ordem integer DEFAULT 0 NOT NULL,
    artista character varying(255),
    letra text,
    nome character varying(150),
    foto character varying(255),
    link character varying(500),
    ano integer
);


ALTER TABLE public.albuns OWNER TO sitehtml;

--
-- Name: albuns_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.albuns_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.albuns_id_seq OWNER TO sitehtml;

--
-- Name: albuns_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.albuns_id_seq OWNED BY public.albuns.id;


--
-- Name: comentarios; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.comentarios (
    id bigint NOT NULL,
    noticia_id bigint NOT NULL,
    comentario text NOT NULL
);


ALTER TABLE public.comentarios OWNER TO sitehtml;

--
-- Name: comentarios_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.comentarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.comentarios_id_seq OWNER TO sitehtml;

--
-- Name: comentarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.comentarios_id_seq OWNED BY public.comentarios.id;


--
-- Name: curtidas; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.curtidas (
    id bigint NOT NULL,
    noticia_id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.curtidas OWNER TO sitehtml;

--
-- Name: curtidas_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.curtidas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.curtidas_id_seq OWNER TO sitehtml;

--
-- Name: curtidas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.curtidas_id_seq OWNED BY public.curtidas.id;


--
-- Name: favoritos; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.favoritos (
    id bigint NOT NULL,
    noticia_id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.favoritos OWNER TO sitehtml;

--
-- Name: favoritos_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.favoritos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.favoritos_id_seq OWNER TO sitehtml;

--
-- Name: favoritos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.favoritos_id_seq OWNED BY public.favoritos.id;


--
-- Name: fotos; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.fotos (
    id integer NOT NULL,
    fotos character varying(255),
    legenda character varying(255)
);


ALTER TABLE public.fotos OWNER TO sitehtml;

--
-- Name: fotos_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.fotos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fotos_id_seq OWNER TO sitehtml;

--
-- Name: fotos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.fotos_id_seq OWNED BY public.fotos.id;


--
-- Name: logados; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.logados (
    id integer NOT NULL,
    name_sobrenome character varying(30) NOT NULL,
    email character varying(30) NOT NULL,
    senha character varying(255) NOT NULL
);


ALTER TABLE public.logados OWNER TO sitehtml;

--
-- Name: logados_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.logados_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.logados_id_seq OWNER TO sitehtml;

--
-- Name: logados_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.logados_id_seq OWNED BY public.logados.id;


--
-- Name: noticias; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.noticias (
    id integer NOT NULL,
    noticia text,
    foto character varying(255),
    link character varying(255),
    link_usuario character varying(500),
    comentarios text,
    titulo character varying(200),
    data_publicacao timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.noticias OWNER TO sitehtml;

--
-- Name: noticias_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.noticias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.noticias_id_seq OWNER TO sitehtml;

--
-- Name: noticias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.noticias_id_seq OWNED BY public.noticias.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: sitehtml
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    name_sobrenome character varying(120) NOT NULL,
    email character varying(160) NOT NULL,
    senha character varying(255) NOT NULL,
    tipo character varying(20) DEFAULT 'usuario'::character varying,
    foto_perfil character varying(255)
);


ALTER TABLE public.usuarios OWNER TO sitehtml;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: sitehtml
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuarios_id_seq OWNER TO sitehtml;

--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sitehtml
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: album_comentarios id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_comentarios ALTER COLUMN id SET DEFAULT nextval('public.album_comentarios_id_seq'::regclass);


--
-- Name: album_curtidas id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_curtidas ALTER COLUMN id SET DEFAULT nextval('public.album_curtidas_id_seq'::regclass);


--
-- Name: album_favoritos id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_favoritos ALTER COLUMN id SET DEFAULT nextval('public.album_favoritos_id_seq'::regclass);


--
-- Name: albuns id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.albuns ALTER COLUMN id SET DEFAULT nextval('public.albuns_id_seq'::regclass);


--
-- Name: comentarios id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.comentarios ALTER COLUMN id SET DEFAULT nextval('public.comentarios_id_seq'::regclass);


--
-- Name: curtidas id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.curtidas ALTER COLUMN id SET DEFAULT nextval('public.curtidas_id_seq'::regclass);


--
-- Name: favoritos id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.favoritos ALTER COLUMN id SET DEFAULT nextval('public.favoritos_id_seq'::regclass);


--
-- Name: fotos id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.fotos ALTER COLUMN id SET DEFAULT nextval('public.fotos_id_seq'::regclass);


--
-- Name: logados id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.logados ALTER COLUMN id SET DEFAULT nextval('public.logados_id_seq'::regclass);


--
-- Name: noticias id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.noticias ALTER COLUMN id SET DEFAULT nextval('public.noticias_id_seq'::regclass);


--
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Data for Name: album_comentarios; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.album_comentarios (id, album_id, comentario) FROM stdin;
1	11	meu album preferido
2	16	a meu album favorito e o primeiiirooo
3	11	apaga esse ai
4	11	Esse é o melhor album deles, amo 1D!!!
5	16	uauu, harry é o melhor emm😊😊
\.


--
-- Data for Name: album_curtidas; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.album_curtidas (id, album_id, usuario_id) FROM stdin;
1	11	6
2	16	6
3	20	11
4	13	20
5	11	19
6	13	19
7	16	21
8	17	21
9	16	23
\.


--
-- Data for Name: album_favoritos; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.album_favoritos (id, album_id, usuario_id) FROM stdin;
1	11	6
2	16	6
3	20	11
4	11	19
5	11	25
\.


--
-- Data for Name: albuns; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.albuns (id, titulo, descricao, tipo, youtube, ordem, artista, letra, nome, foto, link, ano) FROM stdin;
11	Up All Night	Primeiro álbum de estúdio do One Direction, lançado em 2011. Foi o álbum que marcou o início da carreira musical do grupo e trouxe músicas que ficaram muito conhecidas, como What Makes You Beautiful e One Thing.	banda	https://www.youtube.com/results?search_query=One+Direction+Up+All+Night+album	1	\N	\N	\N	\N	\N	\N
12	Take Me Home	Segundo álbum do grupo, lançado em 2012. O álbum continuou o sucesso do One Direction e trouxe músicas como Live While We're Young e Little Things.	banda	https://www.youtube.com/results?search_query=One+Direction+Take+Me+Home+album	2	\N	\N	\N	\N	\N	\N
13	Midnight Memories	Terceiro álbum do One Direction, lançado em 2013. O disco mostrou uma evolução no estilo musical do grupo e trouxe músicas como Best Song Ever, Story of My Life e Midnight Memories.	banda	https://www.youtube.com/results?search_query=One+Direction+Midnight+Memories+album	3	\N	\N	\N	\N	\N	\N
14	FOUR	Quarto álbum do grupo, lançado em 2014. FOUR trouxe uma sonoridade mais madura e músicas como Steal My Girl, Night Changes e Fireproof.	banda	https://www.youtube.com/results?search_query=One+Direction+FOUR+album	4	\N	\N	\N	\N	\N	\N
15	Made In The A.M.	Quinto e último álbum de estúdio do One Direction, lançado em 2015. O álbum inclui músicas como Drag Me Down, Perfect e History.	banda	https://www.youtube.com/results?search_query=One+Direction+Made+In+The+AM+album	5	\N	\N	\N	\N	\N	\N
16	Harry Styles	Harry Styles (2017), Fine Line (2019) e Harry's House (2022) Kiss All the Time. Disco, Occasionally (2026). Após a pausa do grupo, Harry lançou carreira solo de sucesso, com destaque para "Sign of the Times", "Watermelon Sugar" e "As It Was".	solo	https://www.youtube.com/results?search_query=Harry+Styles+albums	1	\N	\N	\N	\N	\N	\N
17	Zayn Malik	Mind of Mine (2016), Icarus Falls (2018) e Nobody Is Listening (2021) Konnnacol (2026). Zayn foi o primeiro a sair do grupo, em 2015, e estreou em carreira solo com o hit "Pillowtalk", seguindo com um estilo mais R&B alternativo.	solo	https://www.youtube.com/results?search_query=Zayn+albums	2	\N	\N	\N	\N	\N	\N
18	Niall Horan	Flicker (2017), Heartbreak Weather (2020) e The Show (2023) Dinner Party (2026). Niall seguiu no estilo pop/folk, com sucessos como "Slow Hands", "Nice To Meet Ya" e "Heaven".	solo	https://www.youtube.com/results?search_query=Niall+Horan+albums	3	\N	\N	\N	\N	\N	\N
19	Louis Tomlinson	Walls (2020) e Faith in the Future (2022) How Did I Get Here? (2026). Louis apostou em um som mais indie rock, com músicas como "Walls", "Kill My Mind" e "Out of My System".	solo	https://www.youtube.com/results?search_query=Louis+Tomlinson+albums	4	\N	\N	\N	\N	\N	\N
20	Liam Payne	LP1 (2019). Liam lançou seu álbum único em carreira solo, com destaque para "Strip That Down", "Familiar" e "For You", Feat com Rita Ora.	solo	https://www.youtube.com/results?search_query=Liam+Payne+albums	5	\N	\N	\N	\N	\N	\N
\.


--
-- Data for Name: comentarios; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.comentarios (id, noticia_id, comentario) FROM stdin;
2	8	muito legal essa noticia
3	5	LQWHRFÇO23IR
4	9	Muito bom!!! amoo muito uau
5	8	amei
6	9	adorei
7	9	que legall
\.


--
-- Data for Name: curtidas; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.curtidas (id, noticia_id, usuario_id, criado_em) FROM stdin;
5	9	19	2026-10-23 09:02:40.872413
6	9	21	2026-10-23 09:08:20.579512
7	8	21	2026-10-23 09:08:23.951421
\.


--
-- Data for Name: favoritos; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.favoritos (id, noticia_id, usuario_id, criado_em) FROM stdin;
5	9	19	2026-10-23 09:03:06.070221
\.


--
-- Data for Name: fotos; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.fotos (id, fotos, legenda) FROM stdin;
\.


--
-- Data for Name: logados; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.logados (id, name_sobrenome, email, senha) FROM stdin;
\.


--
-- Data for Name: noticias; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.noticias (id, noticia, foto, link, link_usuario, comentarios, titulo, data_publicacao) FROM stdin;
5	Acervo de notícias do One Direction no g1: as últimas notícias e reportagens sobre a banda reunidas em um só lugar.		https://g1.globo.com/tudo-sobre/one-direction/	\N	\N	Tudo sobre One Direction (g1)	2026-10-21 10:40:45.915014
6	A banda completa 16 anos nesta quinta-feira (23): relembre a trajetória de Harry, Liam, Louis, Niall e Zayn.		https://www.cnnbrasil.com.br/pop/musica/one-direction-banda-completa-16-anos-nesta-quinta-feira-23/	\N	\N	One Direction completa 16 anos (CNN Brasil)	2026-10-21 10:40:45.928293
7	Primeiro álbum em carreira solo do Zayn Malik rendeu críticas elogiosas após a saída do grupo em 2015.		https://g1.globo.com/musica/noticia/2016/04/zayn-malik-disco-solo-tem-gritos-de-liberdade-indiretas-e-rb-moderninho.html	\N	\N	Disco solo do Zayn tem gritos de liberdade e R&B moderninho (g1)	2026-10-21 10:40:45.945078
8	O One Direction anunciou seu primeiro álbum de estúdio sem Zayn Malik: "Made In The A.M.".		https://g1.globo.com/musica/noticia/2015/09/one-direction-lancara-primeiro-album-sem-zayn-malik-em-novembro.html	\N	\N	Primeiro álbum sem Zayn chega em novembro (g1, 2015)	2026-10-21 10:40:45.957773
9	depois de anos, zayn anuncia seu primeiro show solo, em solo brasileiro.		https://veja.abril.com.br/cultura/zayn-ex-one-direction-anuncia-primeiro-show-solo-no-brasil/	\N	\N	Zayn anuncia seu primeiro show solo.	2026-10-21 13:53:25.6177
10	Ex-One Direction Harry Styles discute com fã em Nova York; entenda		https://revistaquem.globo.com/noticias/noticia/2026/10/ex-one-direction-harry-styles-discute-com-fa-em-nova-york-entenda.ghtml	\N	\N	Harry Styles Briga Com Fã, em Nova York	2026-10-23 09:49:53.86565
11	Zayn, ex-One Direction, usa camisa do Flamengo em show no estádio do Palmeiras e se desculpa			\N	\N	Zayn, ex-One Direction, usa camisa do Flamengo em show no estádio do Palmeiras e se desculpa	2026-10-23 09:51:51.123585
\.


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: sitehtml
--

COPY public.usuarios (id, name_sobrenome, email, senha, tipo, foto_perfil) FROM stdin;
2	mirela lisboa	mirelalisboa@gmail.com	$2y$12$x7mrep7plE0AzI8.xXmAfuVT1LiRQGcw.MtZ2I0CFWBvpi8KF9LC2	admin	\N
3	mirela	mirela@gmail.com	$2y$12$StX7gyEAou/rfy6a2zpjQux4XYPyw4G/x6wxB4HICAlv321AS4BVq	admin	\N
4	mirela lisboa	mirelalisbao@gmail.com	$2y$12$m57YdEOn0msF9S8/pil9IeBK9Fo9bA9uLK2VT.2MMZm6zOWLbMg7a	admin	\N
14	Administrador	mirela.r@edu.senai.br	$2y$12$6a1tfN4Qb.P.6Yt7hd8hAeqYFFFAi7H8m6qIW1AKPJu3TnzmElMFK	admin	\N
17	jorge	jorge@gmail.com	$2y$12$ahBZJPcka1W7Gt/Y4afnr.f0Pt.2b5jArmspcSeq8K893HaW3AQmy	usuario	\N
18	jorge	jorge@gmail.com	$2y$12$4XszWh5HtJ2lMh89kJKR/OqeJJwoEfqaMUX3pLBifq5SCWcr8F4h2	usuario	\N
19	Luna	luna@gmail.com	$2y$12$fNjm7/2vPdM/8wwyyg5mDeEIeEdLsWlfKK9fElwztCH7T2FyrSzQO	usuario	\N
20	tiago	tiago.j.nascimento@edu.senai.br	$2y$12$FlWb6mnctFknbmmCEyD9We.e72zTF.Uz3ndb3DuaoLDChtid6R9km	usuario	\N
21	henrique	lopes@gmail.com	$2y$12$zIdt5dQprBdz0TqOpKqe7OxT//G.7UDVlf2KdIgXEWuNKJVAn3dda	usuario	\N
22	Alef Marcos Rodrigues Alves	alef@gmail.com	$2y$12$1TvSUW3eE/P39Z0xN6J3j.QBt0yx2D9NfU6J0.pZzMkYvs0E8Mxwi	usuario	\N
23	Vitória	vitoria@gmail.com	$2y$12$mlkHEhY5BA4cYA9fwHBZsu/9qKdX1ZB1ZlfjJa937Bh752skSfbjK	usuario	\N
24	madu	madu@gmail.com	$2y$12$OkzLpMaA2jE9nqanZG1C0OgOpo8fJ3lLasYF4FqXWxnNCf5mnHAyC	usuario	\N
25	nicolas rocha damada junior	22@22.com	$2y$12$F50FWBKRq.aZYOMyld4.AuaP8NLihoWFmavyezgwYOOND2Jgd0X22	usuario	\N
16	tiguigo	tiguigo@gmail.com	$2y$12$CTD1rC1xU36rz3jxOGEKvuyQMADmIg4hXpGx0FRsnfL24lmxbKJyC	usuario	\N
26	tiguigo	tiguigo@gmail.com	$2y$12$xnMD6NNitbm3yWvmE/P/F.LnRznmYOAi/9zsMgSpXxgDIDpVaRKCG	usuario	\N
\.


--
-- Name: album_comentarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.album_comentarios_id_seq', 5, true);


--
-- Name: album_curtidas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.album_curtidas_id_seq', 9, true);


--
-- Name: album_favoritos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.album_favoritos_id_seq', 5, true);


--
-- Name: albuns_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.albuns_id_seq', 20, true);


--
-- Name: comentarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.comentarios_id_seq', 7, true);


--
-- Name: curtidas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.curtidas_id_seq', 8, true);


--
-- Name: favoritos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.favoritos_id_seq', 5, true);


--
-- Name: fotos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.fotos_id_seq', 1, false);


--
-- Name: logados_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.logados_id_seq', 1, false);


--
-- Name: noticias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.noticias_id_seq', 11, true);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sitehtml
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 26, true);


--
-- Name: album_comentarios album_comentarios_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_comentarios
    ADD CONSTRAINT album_comentarios_pkey PRIMARY KEY (id);


--
-- Name: album_curtidas album_curtidas_par_unico; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_curtidas
    ADD CONSTRAINT album_curtidas_par_unico UNIQUE (album_id, usuario_id);


--
-- Name: album_curtidas album_curtidas_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_curtidas
    ADD CONSTRAINT album_curtidas_pkey PRIMARY KEY (id);


--
-- Name: album_favoritos album_favoritos_par_unico; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_favoritos
    ADD CONSTRAINT album_favoritos_par_unico UNIQUE (album_id, usuario_id);


--
-- Name: album_favoritos album_favoritos_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_favoritos
    ADD CONSTRAINT album_favoritos_pkey PRIMARY KEY (id);


--
-- Name: albuns albuns_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.albuns
    ADD CONSTRAINT albuns_pkey PRIMARY KEY (id);


--
-- Name: comentarios comentarios_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.comentarios
    ADD CONSTRAINT comentarios_pkey PRIMARY KEY (id);


--
-- Name: curtidas curtidas_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.curtidas
    ADD CONSTRAINT curtidas_pkey PRIMARY KEY (id);


--
-- Name: favoritos favoritos_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_pkey PRIMARY KEY (id);


--
-- Name: fotos fotos_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.fotos
    ADD CONSTRAINT fotos_pkey PRIMARY KEY (id);


--
-- Name: logados logados_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.logados
    ADD CONSTRAINT logados_pkey PRIMARY KEY (id);


--
-- Name: noticias noticias_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.noticias
    ADD CONSTRAINT noticias_pkey PRIMARY KEY (id);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: idx_curtida_unica; Type: INDEX; Schema: public; Owner: sitehtml
--

CREATE UNIQUE INDEX idx_curtida_unica ON public.curtidas USING btree (noticia_id, usuario_id);


--
-- Name: idx_favorito_unico; Type: INDEX; Schema: public; Owner: sitehtml
--

CREATE UNIQUE INDEX idx_favorito_unico ON public.favoritos USING btree (noticia_id, usuario_id);


--
-- Name: album_comentarios album_comentarios_album_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_comentarios
    ADD CONSTRAINT album_comentarios_album_id_fkey FOREIGN KEY (album_id) REFERENCES public.albuns(id) ON DELETE CASCADE;


--
-- Name: album_curtidas album_curtidas_album_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_curtidas
    ADD CONSTRAINT album_curtidas_album_id_fkey FOREIGN KEY (album_id) REFERENCES public.albuns(id) ON DELETE CASCADE;


--
-- Name: album_favoritos album_favoritos_album_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.album_favoritos
    ADD CONSTRAINT album_favoritos_album_id_fkey FOREIGN KEY (album_id) REFERENCES public.albuns(id) ON DELETE CASCADE;


--
-- Name: comentarios comentarios_noticia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.comentarios
    ADD CONSTRAINT comentarios_noticia_id_fkey FOREIGN KEY (noticia_id) REFERENCES public.noticias(id) ON DELETE CASCADE;


--
-- Name: curtidas curtidas_noticia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.curtidas
    ADD CONSTRAINT curtidas_noticia_id_fkey FOREIGN KEY (noticia_id) REFERENCES public.noticias(id) ON DELETE CASCADE;


--
-- Name: curtidas curtidas_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.curtidas
    ADD CONSTRAINT curtidas_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: favoritos favoritos_noticia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_noticia_id_fkey FOREIGN KEY (noticia_id) REFERENCES public.noticias(id) ON DELETE CASCADE;


--
-- Name: favoritos favoritos_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sitehtml
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict vThe4bsRLjmdIxFvHidgvGwrADpFt8oRbKehlrFNGmjxWxTmCqweDtvgVQfBmVY

