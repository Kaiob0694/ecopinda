document.addEventListener("DOMContentLoaded", function () {

    const galerias = document.querySelectorAll(".restaurante-galeria");

    galerias.forEach(function (galeria) {

        const fotos = galeria.querySelectorAll(".restaurante-imagem");

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

});