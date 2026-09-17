/*
|--------------------------------------------------------------------------
| ANIMAÇÕES DO HEADER — GSAP (versão "pílula flutuante")
|--------------------------------------------------------------------------
|
| Requer o GSAP carregado ANTES deste script:
| <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
|
| Efeitos:
| 1) Entrada animada: logo, itens do menu e chip do usuário aparecem
|    em sequência quando a página carrega.
| 2) Blob líquido: pílula colorida que persegue o link em hover,
|    com efeito de mola (elastic.out) e leve esticamento.
| 3) Submenu (Turismo > Guia Turístico) com abertura suave via GSAP.
| 4) Bônus: contador de PindaCOINS sobe de 0 até o valor real na entrada.
|
*/

document.addEventListener('DOMContentLoaded', function () {

    if (typeof gsap === 'undefined') {
        console.warn('GSAP não foi carregado. Inclua o script do GSAP antes deste arquivo.');
        return;
    }

    var navPill = document.querySelector('.nav-pill');

    if (!navPill) {
        return;
    }

    /*
    |----------------------------------------------------------------
    | CORES POR ITEM
    |----------------------------------------------------------------
    */

    var CORES = {
        laranja: getComputedStyle(document.documentElement).getPropertyValue('--laranja').trim(),
        verde: getComputedStyle(document.documentElement).getPropertyValue('--verde').trim(),
        amarelo: getComputedStyle(document.documentElement).getPropertyValue('--amarelo').trim()
    };

    function corDoItem(item) {

        if (item.dataset.indicatorColor && CORES[item.dataset.indicatorColor]) {
            return CORES[item.dataset.indicatorColor];
        }

        if (item.closest('.menu-dropdown')) {
            return CORES.amarelo;
        }

        var diretos = Array.prototype.filter.call(navPill.children, function (el) {
            return el.tagName === 'A' || el.classList.contains('menu-dropdown');
        });

        // "Cidade" é o 2º item direto (mesma regra do design original)
        if (diretos.indexOf(item.closest('.menu-dropdown') || item) === 1) {
            return CORES.verde;
        }

        return CORES.laranja;
    }


    /*
    |----------------------------------------------------------------
    | 1) BLOB LÍQUIDO
    |----------------------------------------------------------------
    */

    var blob = document.createElement('div');
    blob.className = 'nav-blob';
    navPill.insertBefore(blob, navPill.firstChild);

    var itensBlob = navPill.querySelectorAll(':scope > a, .menu-dropdown > a');
    var blobAtivo = null;

    function moverBlob(item) {

        var pillRect = navPill.getBoundingClientRect();
        var itemRect = item.getBoundingClientRect();

        var left = itemRect.left - pillRect.left;

        gsap.killTweensOf(blob);

        var tl = gsap.timeline();

        tl.to(blob, {
            left: left,
            width: itemRect.width,
            backgroundColor: corDoItem(item),
            opacity: 1,
            duration: 0.65,
            ease: 'elastic.out(1, 0.55)'
        }, 0);

        tl.fromTo(blob,
            { scaleX: 1.15, scaleY: 0.75 },
            { scaleX: 1, scaleY: 1, duration: 0.6, ease: 'elastic.out(1, 0.4)', transformOrigin: 'center' },
            0
        );
    }

    itensBlob.forEach(function (item) {
        item.addEventListener('mouseenter', function () {
            blobAtivo = item;
            moverBlob(item);
        });
    });

    navPill.addEventListener('mouseleave', function () {
        blobAtivo = null;
        gsap.killTweensOf(blob);
        gsap.to(blob, {
            scale: 0.4,
            opacity: 0,
            duration: 0.35,
            ease: 'back.in(1.5)',
            transformOrigin: 'center'
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

    var logo = document.querySelector('.logo-pill');
    var itensMenu = navPill.querySelectorAll(':scope > a, .menu-dropdown');
    var acoes = document.querySelector('.header-actions');

    var entrada = gsap.timeline({ defaults: { ease: 'power4.out' } });

    entrada.from('.header-shell', {
        opacity: 0,
        y: -30,
        scale: 0.96,
        duration: 0.7,
        ease: 'power3.out'
    });

    if (logo) {
        entrada.from(logo, {
            opacity: 0,
            x: -30,
            duration: 0.6,
            ease: 'back.out(1.6)'
        }, '-=0.35');
    }

    entrada.from(itensMenu, {
        opacity: 0,
        y: -14,
        duration: 0.5,
        stagger: 0.06
    }, '-=0.3');

    if (acoes) {
        entrada.from(acoes, {
            opacity: 0,
            x: 20,
            duration: 0.5
        }, '-=0.3');
    }


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
            delay: 0.7,
            ease: 'power2.out',
            onUpdate: function () {
                pindaCoinsEl.textContent = 'PindaCOINS: ' + Math.round(contador.valor);
            }
        });
    }

});