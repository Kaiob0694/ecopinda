/*
|--------------------------------------------------------------------------
| PRELOADER — GSAP
|--------------------------------------------------------------------------
|
| Requer o GSAP carregado ANTES deste script:
| <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
|
| Comportamento:
| 1) Ao abrir a página: logo e anel entram com efeito de mola,
|    logo fica "respirando" (pulso suave) enquanto a página carrega.
| 2) As reticências do texto "Carregando..." piscam em sequência.
| 3) Quando a página termina de carregar (window 'load'), a logo dá um
|    "salto" final, o anel some rápido e toda a tela se dissolve,
|    revelando o site por baixo.
| 4) Tempo mínimo de exibição (900ms) para não "piscar" em conexões
|    muito rápidas.
|
*/

(function () {

    var preloader = document.getElementById('preloader');

    if (!preloader) {
        return;
    }

    var logo = preloader.querySelector('.preloader-logo');
    var ring = preloader.querySelector('.preloader-ring');
    var texto = preloader.querySelector('.preloader-text');
    var pontos = preloader.querySelector('.preloader-dots');

    var TEMPO_MINIMO = 900;  // ms — tempo mínimo exibindo o preloader
    var TEMPO_MAXIMO = 5000; // ms — trava de segurança: some sempre, aconteça o que acontecer
    var inicio = Date.now();
    var jaEscondeu = false;

    function iniciarAnimacoes() {

        if (typeof gsap === 'undefined') {
            return;
        }

        var entrada = gsap.timeline({ defaults: { ease: 'power3.out' } });

        entrada
            .from(ring, {
                scale: 0,
                opacity: 0,
                duration: 0.5
            })
            .from(logo, {
                scale: 0,
                rotation: -120,
                duration: 0.8,
                ease: 'elastic.out(1, 0.55)'
            }, '-=0.25')
            .from(texto, {
                opacity: 0,
                y: 8,
                duration: 0.4
            }, '-=0.3');

        // respiração contínua da logo enquanto carrega
        gsap.to(logo, {
            scale: 1.08,
            duration: 1.1,
            yoyo: true,
            repeat: -1,
            ease: 'sine.inOut'
        });

        // reticências piscando uma a uma
        if (pontos) {
            gsap.to(pontos, {
                opacity: 0.2,
                duration: 0.5,
                yoyo: true,
                repeat: -1,
                ease: 'sine.inOut'
            });
        }
    }

    function esconderPreloader() {

        if (jaEscondeu) {
            return;
        }
        jaEscondeu = true;

        var decorrido = Date.now() - inicio;
        var espera = Math.max(0, TEMPO_MINIMO - decorrido);

        setTimeout(function () {

            if (typeof gsap === 'undefined') {
                preloader.style.display = 'none';
                return;
            }

            // mata as animações contínuas (respiração / pontos)
            gsap.killTweensOf(logo);
            if (pontos) {
                gsap.killTweensOf(pontos);
            }

            var saida = gsap.timeline({
                onComplete: function () {
                    preloader.style.display = 'none';
                }
            });

            saida
                .to(logo, {
                    scale: 1.25,
                    rotation: 15,
                    duration: 0.35,
                    ease: 'power2.out'
                })
                .to(ring, {
                    opacity: 0,
                    scale: 1.4,
                    duration: 0.3,
                    ease: 'power2.in'
                }, '<')
                .to(texto, {
                    opacity: 0,
                    y: -8,
                    duration: 0.25
                }, '<')
                .to(preloader, {
                    opacity: 0,
                    scale: 1.05,
                    duration: 0.5,
                    ease: 'power2.inOut'
                }, '-=0.05');

        }, espera);
    }

    iniciarAnimacoes();

    // Gatilho principal: assim que o HTML/CSS/JS da página terminarem
    // de processar — NÃO espera recursos externos lentos (embeds,
    // imagens grandes, etc), que poderiam travar o window 'load'.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', esconderPreloader);
    } else {
        esconderPreloader();
    }

    // Trava de segurança: não importa o que aconteça na página,
    // o preloader nunca fica preso por mais que TEMPO_MAXIMO.
    setTimeout(esconderPreloader, TEMPO_MAXIMO);

})();
