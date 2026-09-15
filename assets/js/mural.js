document.addEventListener("DOMContentLoaded", function () {

    /* =========================================================
       ELEMENTOS
    ========================================================= */

    const modal = document.getElementById("uploadModal");
    const abrirModal = document.getElementById("abrirModalUpload");
    const fecharModalBtn = document.getElementById("fecharModalUpload");

    const form = document.getElementById("formUpload");

    const fotoInput = document.getElementById("fotoInput");
    const uploadArea = document.getElementById("uploadArea");

    const previewContainer = document.getElementById("previewContainer");
    const fotoPreview = document.getElementById("fotoPreview");

    const descricao = document.getElementById("descricao");
    const charCount = document.getElementById("charCount");

    const botaoPublicar = document.getElementById("botaoPublicar");

    const mensagemCoin = document.getElementById("mensagemCoin");


    /* =========================================================
       ABRIR MODAL
    ========================================================= */

    if (abrirModal) {

        abrirModal.addEventListener("click", function () {

            modal.classList.add("ativo");

        });

    }


    /* =========================================================
       FECHAR MODAL
    ========================================================= */

    function fecharModal() {

        modal.classList.remove("ativo");

        form.reset();

        previewContainer.classList.remove("ativo");

        fotoPreview.src = "";

        charCount.textContent = "0";

        botaoPublicar.disabled = false;

        botaoPublicar.textContent = "📸 Publicar foto";

    }


    if (fecharModalBtn) {

        fecharModalBtn.addEventListener("click", function () {

            fecharModal();

        });

    }


    /* =========================================================
       FECHAR CLICANDO FORA
    ========================================================= */

    if (modal) {

        modal.addEventListener("click", function (event) {

            if (event.target === modal) {

                fecharModal();

            }

        });

    }


    /* =========================================================
       SELECIONAR FOTO
    ========================================================= */

    if (uploadArea && fotoInput) {

        uploadArea.addEventListener("click", function () {

            fotoInput.click();

        });


        fotoInput.addEventListener("change", function () {

            if (this.files && this.files.length > 0) {

                mostrarPreview(this.files[0]);

            }

        });

    }


    /* =========================================================
       DRAG AND DROP
    ========================================================= */

    if (uploadArea) {

        uploadArea.addEventListener("dragover", function (event) {

            event.preventDefault();

            uploadArea.classList.add("arrastando");

        });


        uploadArea.addEventListener("dragleave", function () {

            uploadArea.classList.remove("arrastando");

        });


        uploadArea.addEventListener("drop", function (event) {

            event.preventDefault();

            uploadArea.classList.remove("arrastando");

            const arquivos = event.dataTransfer.files;

            if (arquivos.length > 0) {

                fotoInput.files = arquivos;

                mostrarPreview(arquivos[0]);

            }

        });

    }


    /* =========================================================
       PREVIEW
    ========================================================= */

    function mostrarPreview(arquivo) {

        const tiposPermitidos = [
            "image/jpeg",
            "image/png",
            "image/webp",
            "image/gif"
        ];

        if (!tiposPermitidos.includes(arquivo.type)) {

            mostrarMensagem(
                "Tipo de arquivo não permitido. Use JPG, PNG, WEBP ou GIF.",
                "erro"
            );

            fotoInput.value = "";

            return;

        }


        const tamanhoMaximo = 5 * 1024 * 1024;

        if (arquivo.size > tamanhoMaximo) {

            mostrarMensagem(
                "A imagem é muito grande. O tamanho máximo é 5MB.",
                "erro"
            );

            fotoInput.value = "";

            return;

        }


        const leitor = new FileReader();


        leitor.onload = function (event) {

            fotoPreview.src = event.target.result;

            previewContainer.classList.add("ativo");

        };


        leitor.readAsDataURL(arquivo);

    }


    /* =========================================================
       CONTADOR DE CARACTERES
    ========================================================= */

    if (descricao) {

        descricao.addEventListener("input", function () {

            charCount.textContent = this.value.length;

        });

    }


    /* =========================================================
       ENVIO DO FORMULÁRIO
    ========================================================= */

    if (form) {

        form.addEventListener("submit", async function (event) {

            event.preventDefault();


            /* Verifica foto */

            if (!fotoInput.files || fotoInput.files.length === 0) {

                mostrarMensagem(
                    "Selecione uma foto antes de publicar.",
                    "erro"
                );

                return;

            }


            const arquivo = fotoInput.files[0];


            /* Verifica tamanho */

            if (arquivo.size > 5 * 1024 * 1024) {

                mostrarMensagem(
                    "A imagem é muito grande. O tamanho máximo é 5MB.",
                    "erro"
                );

                return;

            }


            /* Desabilita botão */

            botaoPublicar.disabled = true;

            botaoPublicar.textContent = "Enviando...";


            const formData = new FormData();

            formData.append("foto", arquivo);

            formData.append(
                "descricao",
                descricao.value
            );


            try {

                const resposta = await fetch(
                    "upload.php",
                    {
                        method: "POST",
                        body: formData
                    }
                );


                /*
                 * Pegamos primeiro como texto.
                 * Isso evita que um erro PHP transforme
                 * o JSON em um erro obscuro.
                 */

                const texto = await resposta.text();

                console.log("Resposta do upload:", texto);


                let data;


                try {

                    data = JSON.parse(texto);

                } catch (erroJson) {

                    console.error(
                        "Resposta não é JSON:",
                        texto
                    );

                    throw new Error(
                        "O servidor não retornou uma resposta válida."
                    );

                }


                console.log(
                    "Dados recebidos:",
                    data
                );


                /* =================================================
                   SUCESSO
                ================================================= */

                if (data.sucesso) {

                    fecharModal();


                    const moedas =
                        data.pindacoins_ganhos ?? 10;


                    mostrarMensagem(
                        `🎉 Foto publicada com sucesso! Você ganhou +${moedas} PindaCoins!`,
                        "sucesso"
                    );


                    /*
                     * Atualiza a quantidade de coins no header,
                     * caso exista um elemento com este ID.
                     */

                    const contadorCoins =
                        document.getElementById("pindacoins");

                    if (contadorCoins) {

                        const valorAtual =
                            parseInt(
                                contadorCoins.textContent
                            ) || 0;

                        contadorCoins.textContent =
                            valorAtual + moedas;

                    }


                    /*
                     * Recarrega apenas depois de 5 segundos.
                     *
                     * Se você NÃO quiser recarregar a página,
                     * pode apagar este setTimeout.
                     */

                    setTimeout(function () {

                        window.location.reload();

                    }, 5000);


                } else {

                    mostrarMensagem(
                        data.mensagem ||
                        "Não foi possível publicar a foto.",
                        "erro"
                    );


                    botaoPublicar.disabled = false;

                    botaoPublicar.textContent =
                        "📸 Publicar foto";

                }


            } catch (erro) {

                console.error(
                    "Erro no upload:",
                    erro
                );


                mostrarMensagem(
                    erro.message ||
                    "Erro ao enviar a foto.",
                    "erro"
                );


                botaoPublicar.disabled = false;

                botaoPublicar.textContent =
                    "📸 Publicar foto";

            }

        });

    }


    /* =========================================================
       MENSAGEM
    ========================================================= */

    function mostrarMensagem(texto, tipo) {

        if (!mensagemCoin) {

            console.error(
                "Elemento #mensagemCoin não encontrado."
            );

            return;

        }


        mensagemCoin.className = "mensagem-coin";


        if (tipo === "sucesso") {

            mensagemCoin.classList.add(
                "mensagem-sucesso"
            );

        } else {

            mensagemCoin.classList.add(
                "mensagem-erro"
            );

        }


        mensagemCoin.innerHTML = `
            <div class="mensagem-coin-icone">
                ${tipo === "sucesso" ? "🎉" : "⚠️"}
            </div>

            <div class="mensagem-coin-conteudo">
                ${texto}
            </div>

            <button
                type="button"
                class="mensagem-coin-fechar"
                aria-label="Fechar mensagem"
            >
                ×
            </button>
        `;


        /*
         * Força o navegador a reconhecer a alteração
         * antes de adicionar a classe visível.
         */

        requestAnimationFrame(function () {

            mensagemCoin.classList.add("mostrar");

        });


        const botaoFechar =
            mensagemCoin.querySelector(
                ".mensagem-coin-fechar"
            );


        if (botaoFechar) {

            botaoFechar.addEventListener(
                "click",
                function () {

                    esconderMensagem();

                }
            );

        }


        /*
         * Remove automaticamente depois de 5 segundos.
         */

        setTimeout(function () {

            esconderMensagem();

        }, 5000);

    }


    function esconderMensagem() {

        if (!mensagemCoin) {
            return;
        }

        mensagemCoin.classList.remove("mostrar");

    }

});