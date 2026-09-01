<?php include '../includes/head.php'; ?>
<link rel="stylesheet" href="../assets/css/style_cidade.css">
<?php include '../includes/header.php'; ?>

<section class="hero">
    <video autoplay muted loop playsinline class="video-bg">
        <source src="../assets/video/cidade.mp4" type="video/mp4">
        Seu navegador não suporta a tag de vídeo.
    </video>
</section>

<section class="cachoeira">
    <div id="titulo">
        <h3>ONDE IR</h3>
        <h1>Escolha sua própria jornada</h1>
        <p>Conheça Pindamonhangaba, um destino que reúne natureza, história e tradição no coração do Vale do
            Paraíba. A cidade oferece belas paisagens da Serra da Mantiqueira, trilhas, áreas de lazer e opções de
            turismo rural que proporcionam contato direto com a natureza. Seu patrimônio histórico e cultural
            preserva a identidade local por meio de igrejas, museus e manifestações culturais.</p>
    </div>
</section>

<section class="info-grid">

    <div class="info-card" data-card="historia">
        <div class="info-card-inner">
            <div class="info-card-front">
                <img src="../assets/img2/historia.png" alt="História de Pindamonhangaba">
                <span class="tap">toque para saber mais</span>
                <h3>História da Cidade</h3>
            </div>
            <div class="info-card-back">
                <h4>História da Cidade</h4>
                <p>Fundada no início do século XVIII, Pindamonhangaba preserva um centro histórico com casarões,
                    igrejas e praças que contam a trajetória da cidade desde o ciclo do café até os dias de
                    hoje.</p>
            </div>
        </div>
    </div>

    <div class="info-card" data-card="gastronomia">
        <div class="info-card-inner">
            <div class="info-card-front">
                <img src="../assets/img2/gastronomia.png" alt="Gastronomia local">
                <span class="tap">toque para saber mais</span>
                <h3>Gastronomia</h3>
            </div>
            <div class="info-card-back">
                <h4>Gastronomia</h4>
                <p>Dos restaurantes tradicionais aos cafés e cantinas familiares, a culinária local mistura sabores
                    do interior paulista com opções para todos os gostos.</p>
                <a href="restaurante.php" style="color:#fff;margin-top:14px;font-weight:600;">Ver restaurantes →</a>
            </div>
        </div>
    </div>

    <div class="info-card" data-card="esporte">
        <div class="info-card-inner">
            <div class="info-card-front">
                <img src="../assets/img2/esporte.png" alt="Esportes e lazer">
                <span class="tap">toque para saber mais</span>
                <h3>Esportes e Lazer</h3>
            </div>
            <div class="info-card-back">
                <h4>Esportes e Lazer</h4>
                <p>Trilhas na Serra da Mantiqueira, parques urbanos, ciclovias e áreas de lazer às margens do Rio
                    Paraíba oferecem opções para quem busca ar livre e atividade física.</p>
            </div>
        </div>
    </div>

    <div class="info-card" data-card="transporte">
        <div class="info-card-inner">
            <div class="info-card-front">
                <img src="../assets/img2/transporte.png" alt="Transporte">
                <span class="tap">toque para saber mais</span>
                <h3>Transporte</h3>
            </div>
            <div class="info-card-back">
                <h4>Transporte</h4>
                <p>Localizada às margens da Rodovia Presidente Dutra, a cidade conta com fácil acesso rodoviário,
                    terminal de ônibus urbano e ligação direta com São Paulo e o Vale do Paraíba.</p>
            </div>
        </div>
    </div>

    <div class="info-card" data-card="saude">
        <div class="info-card-inner">
            <div class="info-card-front">
                <img src="../assets/img2/saude.png" alt="Saúde">
                <span class="tap">toque para saber mais</span>
                <h3>Saúde</h3>
            </div>
            <div class="info-card-back">
                <h4>Saúde</h4>
                <p>A rede de saúde local conta com hospitais, UPAs e postos de atendimento distribuídos pelos
                    principais bairros da cidade, garantindo suporte a moradores e visitantes.</p>
            </div>
        </div>
    </div>

    <div class="info-card" data-card="comunidade">
        <div class="info-card-inner">
            <div class="info-card-front">
                <img src="../assets/img2/comunidade.png" alt="Comunidade">
                <span class="tap">toque para saber mais</span>
                <h3>Comunidade</h3>
            </div>
            <div class="info-card-back">
                <h4>Comunidade</h4>
                <p>Feiras livres, eventos culturais e festas tradicionais movimentam o calendário da cidade ao
                    longo do ano, reforçando os laços entre moradores e visitantes.</p>
            </div>
        </div>
    </div>

    <div class="info-card" data-card="apoio">
        <div class="info-card-inner">
            <div class="info-card-front">
                <img src="../assets/img2/apoio.png" alt="Apoio ao turismo">
                <span class="tap">toque para saber mais</span>
                <h3>Apoio ao Turismo</h3>
            </div>
            <div class="info-card-back">
                <h4>Apoio ao Turismo</h4>
                <p>Centros de atendimento ao turista, sinalização nos principais pontos e material informativo
                    ajudam quem visita a cidade a planejar melhor o roteiro.</p>
            </div>
        </div>
    </div>

</section>

<section class="descubra-titulo">
    <h2><span class="fino">Descubra</span> <strong class="verde">mais</strong></h2>
    <p>Encontre onde ficar e onde comer, com opções cadastradas pela própria comunidade de Pindamonhangaba.</p>
</section>

<section class="cta-descubra">

    <a href="hoteis.php" class="cta-card hoteis">
        <i class="fa-solid fa-bed icone-cta"></i>
        <i class="fa-solid fa-arrow-right seta"></i>
        <h3>Hospedagem</h3>
        <p>Veja hotéis e pousadas cadastrados na cidade e escolha onde se hospedar durante a sua visita.</p>
    </a>

    <a href="restaurante.php" class="cta-card restaurantes">
        <i class="fa-solid fa-utensils icone-cta"></i>
        <i class="fa-solid fa-arrow-right seta"></i>
        <h3>Gastronomia</h3>
        <p>Explore restaurantes locais e, se você tem um negócio na área, cadastre o seu no site.</p>
    </a>

</section>

<section class="instagram">
    <h2>Momentos de Pindamonhangaba</h2>
    <p>Acompanhe fotos e experiências compartilhadas no Instagram</p>

    <div class="instagram-feed">

        <blockquote class="instagram-media"
            data-instgrm-permalink="https://www.instagram.com/p/CPlQOuWNgIK/?utm_source=ig_web_copy_link"
            data-instgrm-version="14">
        </blockquote>

        <blockquote class="instagram-media"
            data-instgrm-permalink="https://www.instagram.com/reel/DSTNMhKgdd1/?utm_source=ig_web_copy_link"
            data-instgrm-version="14">
        </blockquote>

        <blockquote class="instagram-media"
            data-instgrm-permalink="https://www.instagram.com/p/DSUtRn9jViu/?utm_source=ig_web_copy_link"
            data-instgrm-version="14">
        </blockquote>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<script>
    // Flip dos cards "Onde ir"
    document.querySelectorAll('.info-card').forEach(function (card) {
        card.addEventListener('click', function () {
            card.classList.toggle('ativo');
        });
    });

    // Animação de entrada ao rolar a página
    if (window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);

        gsap.utils.toArray('.info-card').forEach(function (el, i) {
            gsap.from(el, {
                opacity: 0,
                y: 40,
                duration: .6,
                delay: (i % 4) * .08,
                scrollTrigger: {
                    trigger: el,
                    start: 'top 88%'
                }
            });
        });

        gsap.utils.toArray('.cta-card').forEach(function (el, i) {
            gsap.from(el, {
                opacity: 0,
                y: 40,
                duration: .6,
                delay: i * .1,
                scrollTrigger: {
                    trigger: el,
                    start: 'top 88%'
                }
            });
        });
    }
</script>