<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Direction</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Federo&display=swap');

    /* Variáveis de Cores (Paleta Selecionada) */
    :root {
        --color-light-blue: #B4C4D4;  /* 1º bloco: Azul-claro / Cinza-azulado */
        --color-red: #A63437;         /* 2º bloco: Vermelho / Encarnado */
        --color-dark-gray: #262626;   /* 3º bloco: Cinza-escuro / Preto suave */
        --color-beige: #A08175;       /* 4º bloco: Castanho-claro / Bege escuro */
        --color-burgundy: #4F1B27;    /* 5º bloco: Vinho / Bordô */

        --white: #ffffff;
        --text-dark: #262626;
    }

    /* Reset básico e tipografia */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Georgia, 'Times New Roman', Times, serif;
        background-color: var(--color-dark-gray);
        color: var(--white);
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
        padding: 20px 15px;
    }

    /* Container para centralizar o título na página */
    .typing-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin: 20px 0;
    }

    /* Título principal centralizado com efeito de digitação e fonte Erica One */
    h1 {
        color: var(--color-light-blue);
        font-size: clamp(1.2rem, 3.5vw, 2.2rem);
        font-weight: 400;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-family: Georgia, 'Times New Roman', Times, serif;
        text-align: center;

        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        border-right: 3px solid var(--color-light-blue);

        /* steps(30) para contar exatamente os 30 caracteres de " BEM VINDOS AO BLOG DIRECTIONER" */
        width: 0;
        animation:
            typing 3.5s steps(30) forwards,
            blink-caret 0.75s step-end infinite;
    }

    /* Keyframes da digitação */
    @keyframes typing {
        from {
            width: 0;
        }

        to {
            width: 100%;
        }
    }

    /* Keyframes do cursor a piscar */
    @keyframes blink-caret {

        from,
        to {
            border-color: transparent;
        }

        50% {
            border-color: var(--color-light-blue);
        }
    }

    /* Linhas divisórias (hr) */
    hr {
        border: none;
        height: 3px;
        background: linear-gradient(90deg, var(--color-red), var(--color-burgundy));
        width: 100%;
        max-width: 1000px;
        margin: 15px 0;
        border-radius: 5px;
    }

    /* Estilização do Vídeo Responsivo */
    video {
        width: 100%;
        max-width: 1000px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        margin: 10px 0;
    }

    /* Conteúdo Principal / Botão */
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
    }

    button {
        background-color: var(--color-red);
        color: var(--white);
        font-family: Georgia, 'Times New Roman', Times, serif;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 14px 36px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    button:hover {
        background-color: var(--color-burgundy);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    }

    button:active {
        transform: translateY(0);
    }
</style>

<body>
    <h1> BEM VINDOS, AO BLOG DIRECTIONER!</h1>
    <hr>
    <video width="1000" controls autoplay muted playsinline loop>
        <source src="/videos/video.mp4/videomp4.png.mp4" type="video/mp4">
        O seu navegador não suporta a tag de vídeo.
    </video>
    <?php include __DIR__ . '/includes/header.php';

    ?>
    <hr>

    <main>
        <form action="" method="post">

            <button type="button" onclick="window.location.href='login/login.php'">
                Continuar
            </button>
        </form>
    </main>


    <hr>
    <?php include __DIR__ . '/includes/footer.php';
    ?>
</body>

</html>