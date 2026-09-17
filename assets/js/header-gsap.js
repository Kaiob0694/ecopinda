/*
|--------------------------------------------------------------------------
| ANIMAÇÕES DO HEADER — GSAP
|--------------------------------------------------------------------------
|
| Requer o GSAP carregado ANTES deste script:
| <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
|
| Efeitos:
| 1) Entrada animada: logo + itens do menu + bloco do usuário aparecem
|    em sequência quando a página carrega.
| 2) Blob elástico: uma "gota" líquida que persegue o item do menu em
|    hover, com efeito de mola (elastic.out) e leve esticamento.
| 3) Submenu (Turismo > Guia Turístico) com abertura suave via GSAP.
| 4) Bônus: contador de PindaCOINS sobe de 0 até o valor real na entrada.
|
*/

document.addEventListener('DOMContentLoaded', function () {

    if (typeof gsap === 'undefined') {
        console.warn('GSAP não foi carregado. Inclua o script do GSAP antes deste arquivo.');
        return;
    }

    var menu = document.querySelector('.menu');

    if (!menu) {
        return;
    }

    /*
    |----------------------------------------------------------------
    | CORES POR ITEM (mesma regra do design original)
    |----------------------------------------------------------------
    */

    var CORES = {
        laranja: getComputedStyle(document.documentElement)
            .getPropertyValue('--laranja').trim(),
        verde: getComputedStyle(document.documentElement)
            .getPropertyValue('--verde').trim(),
        amarelo: getComputedStyle(document.documentElement)
            .getPropertyValue('--amarelo').trim()
    };

    function corDoItem(item) {

        if (item.dataset.indicatorColor && CORES[item.dataset.indicatorColor]) {
            return CORES[item.dataset.indicatorColor];
        }

        if (item.closest('.menu-dropdown')) {
            return CORES.amarelo;
        }

        var diretos = Array.prototype.filter.call(menu.children, function (el) {
            return el.tagName === 'A';
        });

        // "Cidade" é o 2º link direto (mesma regra do CSS original)
        if (diretos.indexOf(item) === 1) {
            return CORES.verde;
        }

        return CORES.laranja;
    }


    /*
    |----------------------------------------------------------------
    | 1) BLOB ELÁSTICO
    |----------------------------------------------------------------
    */

    var blob = document.createElement('div');
    blob.className = 'menu-blob';
    menu.appendChild(blob);

    var itensBlob = menu.querySelectorAll(':scope > a, .menu-dropdown > a, .menu-usuario');
    var blobAtivo = null;

    function moverBlob(item) {

        var menuRect = menu.getBoundingClientRect();
        var itemRect = item.getBoundingClientRect();

        var left = itemRect.left - menuRect.left;
        var height = itemRect.height * 0.85;

        gsap.killTweensOf(blob);

        var tl = gsap.timeline();

        // Movimento principal com mola (chega "quicando" de leve)
        tl.to(blob, {
            left: left,
            width: itemRect.width,
            height: height,
            top: itemRect.height - height,
            backgroundColor: corDoItem(item),
            opacity: 1,
            duration: 0.7,
            ease: 'elastic.out(1, 0.55)'
        }, 0);

        // Esticamento líquido: espreme e volta ao normal
        tl.fromTo(blob,
            { scaleX: 1.18, scaleY: 0.7 },
            { scaleX: 1, scaleY: 1, duration: 0.65, ease: 'elastic.out(1, 0.4)', transformOrigin: 'bottom center' },
            0
        );
    }

    itensBlob.forEach(function (item) {
        item.addEventListener('mouseenter', function () {
            blobAtivo = item;
            moverBlob(item);
        });
    });

    menu.addEventListener('mouseleave', function () {
        blobAtivo = null;
        gsap.killTweensOf(blob);
        gsap.to(blob, {
            scaleY: 0.3,
            opacity: 0,
            duration: 0.4,
            ease: 'back.in(1.5)',
            transformOrigin: 'bottom center'
        });
    });

    window.addEventListener('resize', function () {
        if (blobAtivo) {
            moverBlob(blobAtivo);
        }
    });


    /*
    |----------------------------------------------------------------
    | 2) SUBMENU (Turismo > Guia Turístico)
    |----------------------------------------------------------------
    */

    var dropdown = document.querySelector('.menu-dropdown');
    var submenu = dropdown ? dropdown.querySelector('.submenu') : null;

    if (dropdown && submenu) {

        dropdown.addEventListener('mouseenter', function () {
            gsap.killTweensOf(submenu);
            gsap.to(submenu, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.5,
                ease: 'back.out(1.7)',
                pointerEvents: 'auto'
            });
        });

        dropdown.addEventListener('mouseleave', function () {
            gsap.killTweensOf(submenu);
            gsap.to(submenu, {
                opacity: 0,
                y: -10,
                scale: 0.95,
                duration: 0.3,
                ease: 'power2.in',
                pointerEvents: 'none'
            });
        });
    }


    /*
    |----------------------------------------------------------------
    | 3) ANIMAÇÃO DE ENTRADA
    |----------------------------------------------------------------
    */

    var logo = document.querySelector('.logo');
    var linksMenu = menu.querySelectorAll(':scope > a, .menu-dropdown, .menu-usuario, .menu > a[href*="login"]');
    var blocoUsuario = document.querySelector('.menu-usuario');

    var entrada = gsap.timeline({ defaults: { ease: 'power4.out' } });

    if (logo) {
        entrada.from(logo, {
            opacity: 0,
            x: -50,
            duration: 0.8,
            ease: 'back.out(1.6)'
        });
    }

    entrada.from(linksMenu, {
        opacity: 0,
        y: -25,
        duration: 0.6,
        stagger: 0.08
    }, '-=0.35');

    // Barrinha final "assentando" — reaproveita o blob já visível no 1º item se quiser;
    // aqui só garantimos que o header pareça "vivo" assim que carrega.


    /*
    |----------------------------------------------------------------
    | 4) BÔNUS: CONTADOR DE PINDACOINS SUBINDO
    |----------------------------------------------------------------
    */

    var pindaCoinsEl = document.getElementById('pindacoins');

    if (pindaCoinsEl) {

        var textoOriginal = pindaCoinsEl.textContent;
        var valorFinal = parseInt(textoOriginal.replace(/\D/g, ''), 10) || 0;
        var contador = { valor: 0 };

        gsap.to(contador, {
            valor: valorFinal,
            duration: 1.2,
            delay: 0.6,
            ease: 'power2.out',
            onUpdate: function () {
                pindaCoinsEl.textContent = 'PindaCOINS: ' + Math.round(contador.valor);
            }
        });
    }

});
