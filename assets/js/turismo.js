document.addEventListener("DOMContentLoaded", function () {

    const galerias = document.querySelectorAll(".turismo-galeria");

    galerias.forEach(function (galeria) {

        const fotos = galeria.querySelectorAll(".turismo-imagem");

        const anterior = galeria.querySelector(".galeria-anterior");
        const proxima = galeria.querySelector(".galeria-proxima");
        const contador = galeria.querySelector(".galeria-atual");

        let fotoAtual = 0;


        function mostrarFoto(indice) {

            fotos.forEach(function (foto) {
                foto.classList.remove("ativa");
            });

            fotos[indice].classList.add("ativa");

            if (contador) {
                contador.textContent = indice + 1;
            }
        }


        if (proxima) {

            proxima.addEventListener("click", function () {

                fotoAtual++;

                if (fotoAtual >= fotos.length) {
                    fotoAtual = 0;
                }

                mostrarFoto(fotoAtual);

            });

        }


        if (anterior) {

            anterior.addEventListener("click", function () {

                fotoAtual--;

                if (fotoAtual < 0) {
                    fotoAtual = fotos.length - 1;
                }

                mostrarFoto(fotoAtual);

            });

        }

    });


    /*
     * =====================================================
     * ABRIR PÁGINA DE DETALHES AO CLICAR NO CARD
     * =====================================================
     */

    const cardsTurismo = document.querySelectorAll(".turismo-card-clicavel[data-id]");

    cardsTurismo.forEach(function (card) {

        card.addEventListener("click", function (evento) {

            if (evento.target.closest("a, button")) {
                return;
            }

            const id = card.getAttribute("data-id");

            window.location.href = "detalhes.php?id=" + id;

        });

    });

});
