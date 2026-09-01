document.addEventListener("DOMContentLoaded", function () {

    const fotos = window.hotelFotos || [];

    if (fotos.length === 0) {
        return;
    }

    const lightbox = document.getElementById("hotelLightbox");
    const imagemLightbox = lightbox.querySelector(".hotel-lightbox-imagem");
    const contador = lightbox.querySelector(".hotel-lightbox-contador");

    const botaoFechar = lightbox.querySelector(".hotel-lightbox-fechar");
    const botaoAnterior = lightbox.querySelector(".hotel-lightbox-anterior");
    const botaoProxima = lightbox.querySelector(".hotel-lightbox-proxima");

    let indiceAtual = 0;


    function abrirLightbox(indice) {

        indiceAtual = indice;

        mostrarFotoAtual();

        lightbox.classList.add("aberto");

        document.body.style.overflow = "hidden";

    }


    function fecharLightbox() {

        lightbox.classList.remove("aberto");

        document.body.style.overflow = "";

    }


    function mostrarFotoAtual() {

        imagemLightbox.src = fotos[indiceAtual];

        contador.textContent = (indiceAtual + 1) + " / " + fotos.length;

    }


    function fotoAnterior() {

        indiceAtual = (indiceAtual - 1 + fotos.length) % fotos.length;

        mostrarFotoAtual();

    }


    function fotoProxima() {

        indiceAtual = (indiceAtual + 1) % fotos.length;

        mostrarFotoAtual();

    }


    /*
     * =====================================================
     * MINIATURAS DA GALERIA PRINCIPAL
     * =====================================================
     */

    document.querySelectorAll(".hotel-detalhes-foto[data-indice]").forEach(function (botaoFoto) {

        botaoFoto.addEventListener("click", function () {

            const indice = parseInt(botaoFoto.getAttribute("data-indice"), 10);

            abrirLightbox(indice);

        });

    });


    /*
     * =====================================================
     * BOTÃO "VER TODAS AS FOTOS"
     * =====================================================
     */

    const botaoVerTodas = document.querySelector(".hotel-detalhes-ver-todas");

    if (botaoVerTodas) {

        botaoVerTodas.addEventListener("click", function () {

            abrirLightbox(0);

        });

    }


    /*
     * =====================================================
     * CONTROLES DO LIGHTBOX
     * =====================================================
     */

    botaoFechar.addEventListener("click", fecharLightbox);

    botaoAnterior.addEventListener("click", fotoAnterior);

    botaoProxima.addEventListener("click", fotoProxima);

    lightbox.addEventListener("click", function (evento) {

        if (evento.target === lightbox) {
            fecharLightbox();
        }

    });

    document.addEventListener("keydown", function (evento) {

        if (!lightbox.classList.contains("aberto")) {
            return;
        }

        if (evento.key === "Escape") {
            fecharLightbox();
        } else if (evento.key === "ArrowLeft") {
            fotoAnterior();
        } else if (evento.key === "ArrowRight") {
            fotoProxima();
        }

    });

});
