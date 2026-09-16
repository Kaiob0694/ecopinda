document.addEventListener("DOMContentLoaded", function () {

    /* =========================================================
       ELEMENTOS
       ========================================================= */

    const uploadModal =
        document.getElementById("uploadModal");

    const fotoInput =
        document.getElementById("fotoInput");

    const uploadArea =
        document.getElementById("uploadArea");

    const formUpload =
        document.getElementById("formUpload");

    const descricao =
        document.getElementById("descricao");

    const charCount =
        document.getElementById("charCount");

    const previewContainer =
        document.getElementById("previewContainer");

    const fotoPreview =
        document.getElementById("fotoPreview");


    /* =========================================================
       ABRIR MODAL
       ========================================================= */

    function abrirModal() {

        if (!uploadModal) {
            return;
        }

        uploadModal.style.display = "flex";
    }


    /* =========================================================
       FECHAR MODAL
       ========================================================= */

    function fecharModal() {

        if (uploadModal) {
            uploadModal.style.display = "none";
        }

        if (formUpload) {
            formUpload.reset();
        }

        if (previewContainer) {
            previewContainer.style.display = "none";
        }

        if (fotoPreview) {
            fotoPreview.src = "";
        }

        if (charCount) {
            charCount.textContent = "0/500";
        }

        if (uploadArea) {
            uploadArea.classList.remove("dragover");
        }
    }


    /* =========================================================
       LIMPAR FOTO
       ========================================================= */

    function limparFoto() {

        if (fotoInput) {
            fotoInput.value = "";
        }

        if (previewContainer) {
            previewContainer.style.display = "none";
        }

        if (fotoPreview) {
            fotoPreview.src = "";
        }
    }


    /* =========================================================
       DISPONIBILIZAR FUNÇÕES PARA O HTML
       ========================================================= */

    window.abrirModal = abrirModal;

    window.fecharModal = fecharModal;

    window.limparFoto = limparFoto;


    /* =========================================================
       CONTADOR DA DESCRIÇÃO
       ========================================================= */

    if (descricao && charCount) {

        descricao.addEventListener(
            "input",
            function () {

                charCount.textContent =
                    `${this.value.length}/500`;

            }
        );

    }


    /* =========================================================
       SELEÇÃO DE FOTO
       ========================================================= */

    if (fotoInput) {

        fotoInput.addEventListener(
            "change",
            function () {

                validarFoto(this.files[0]);

            }
        );

    }


    /* =========================================================
       DRAG AND DROP
       ========================================================= */

    if (uploadArea) {

        uploadArea.addEventListener(
            "dragover",
            function (e) {

                e.preventDefault();

                uploadArea.classList.add(
                    "dragover"
                );

            }
        );


        uploadArea.addEventListener(
            "dragleave",
            function () {

                uploadArea.classList.remove(
                    "dragover"
                );

            }
        );


        uploadArea.addEventListener(
            "drop",
            function (e) {

                e.preventDefault();

                uploadArea.classList.remove(
                    "dragover"
                );


                const arquivo =
                    e.dataTransfer.files[0];


                if (
                    arquivo &&
                    fotoInput
                ) {

                    fotoInput.files =
                        e.dataTransfer.files;

                    validarFoto(arquivo);

                }

            }
        );


        uploadArea.addEventListener(
            "click",
            function () {

                if (fotoInput) {
                    fotoInput.click();
                }

            }
        );

    }


    /* =========================================================
       VALIDAR FOTO
       ========================================================= */

    function validarFoto(arquivo) {

        if (!arquivo) {
            return;
        }


        const tiposPermitidos = [
            "image/jpeg",
            "image/png",
            "image/webp",
            "image/gif"
        ];


        const tamanhoMaximo =
            5 * 1024 * 1024;


        if (!tiposPermitidos.includes(arquivo.type)) {

            mostrarMensagem(
                "Formato de imagem não permitido. Use JPG, PNG, WEBP ou GIF.",
                "erro"
            );


            if (fotoInput) {
                fotoInput.value = "";
            }

            return;
        }


        if (arquivo.size > tamanhoMaximo) {

            mostrarMensagem(
                "A imagem deve ter no máximo 5 MB.",
                "erro"
            );


            if (fotoInput) {
                fotoInput.value = "";
            }

            return;
        }


        const reader =
            new FileReader();


        reader.onload =
            function (e) {

                if (fotoPreview) {

                    fotoPreview.src =
                        e.target.result;

                }


                if (previewContainer) {

                    previewContainer.style.display =
                        "block";

                }

            };


        reader.readAsDataURL(arquivo);

    }


    /* =========================================================
       ENVIO DO FORMULÁRIO
       ========================================================= */

    if (formUpload) {

        formUpload.addEventListener(
            "submit",
            async function (e) {

                e.preventDefault();


                const arquivo =
                    fotoInput
                        ? fotoInput.files[0]
                        : null;


                if (!arquivo) {

                    mostrarMensagem(
                        "Selecione uma foto antes de publicar.",
                        "erro"
                    );

                    return;
                }


                const formData =
                    new FormData();


                formData.append(
                    "foto",
                    arquivo
                );


                formData.append(
                    "descricao",
                    descricao
                        ? descricao.value
                        : ""
                );


                const botao =
                    formUpload.querySelector(
                        'button[type="submit"]'
                    );


                if (botao) {

                    botao.disabled = true;

                    botao.textContent =
                        "Publicando...";

                }


                try {

                    const resposta =
                        await fetch(
                            "upload.php",
                            {
                                method: "POST",
                                body: formData
                            }
                        );


                    if (!resposta.ok) {

                        throw new Error(
                            `Erro HTTP ${resposta.status}`
                        );

                    }


                    const data =
                        await resposta.json();


                    /* =================================================
                       SUCESSO
                       ================================================= */

                    if (data.sucesso) {

                        fecharModal();


                        /* =============================================
                           ATUALIZAR PINDA COINS NO HEADER
                           ============================================= */

                        const elementoCoins =
                            document.getElementById(
                                "pindacoins"
                            );


                        if (
                            elementoCoins &&
                            data.pindacoins_ganhos !== undefined
                        ) {

                            const textoAtual =
                                elementoCoins.textContent;


                            const saldoAtual =
                                parseInt(
                                    textoAtual.replace(
                                        /\D/g,
                                        ""
                                    ),
                                    10
                                ) || 0;


                            const moedasGanhos =
                                parseInt(
                                    data.pindacoins_ganhos,
                                    10
                                ) || 0;


                            const novoSaldo =
                                saldoAtual +
                                moedasGanhos;


                            elementoCoins.textContent =
                                `🪙 ${novoSaldo}`;

                        }


                        mostrarMensagem(
                            data.mensagem ||
                            "🎉 Foto publicada com sucesso!",
                            "sucesso"
                        );


                        /*
                         * Recarrega o mural depois de
                         * um pequeno intervalo para
                         * mostrar a nova foto.
                         */

                        setTimeout(
                            function () {

                                window.location.reload();

                            },
                            1200
                        );


                    } else {

                        mostrarMensagem(
                            data.mensagem ||
                            "Não foi possível publicar a foto.",
                            "erro"
                        );

                    }


                } catch (erro) {

                    console.error(
                        "Erro no upload:",
                        erro
                    );


                    mostrarMensagem(
                        "Ocorreu um erro ao enviar a foto. Tente novamente.",
                        "erro"
                    );


                } finally {

                    if (botao) {

                        botao.disabled = false;

                        botao.textContent =
                            "Enviar Foto";

                    }

                }

            }
        );

    }


    /* =========================================================
       MENSAGENS
       ========================================================= */

    function mostrarMensagem(
        mensagem,
        tipo = "sucesso"
    ) {

        const mensagemAnterior =
            document.querySelector(
                ".mensagem-mural"
            );


        if (mensagemAnterior) {
            mensagemAnterior.remove();
        }


        const mensagemDiv =
            document.createElement(
                "div"
            );


        mensagemDiv.className =
            `mensagem-mural mensagem-${tipo}`;


        mensagemDiv.innerHTML = `
            <span>${mensagem}</span>

            <button
                type="button"
                class="fechar-mensagem"
            >
                ×
            </button>
        `;


        document.body.appendChild(
            mensagemDiv
        );


        const botaoFechar =
            mensagemDiv.querySelector(
                ".fechar-mensagem"
            );


        if (botaoFechar) {

            botaoFechar.addEventListener(
                "click",
                function () {

                    mensagemDiv.remove();

                }
            );

        }


        setTimeout(
            function () {

                if (
                    mensagemDiv.parentElement
                ) {

                    mensagemDiv.remove();

                }

            },
            5000
        );

    }


    /* =========================================================
       FECHAR CLICANDO FORA DO MODAL
       ========================================================= */

    if (uploadModal) {

        uploadModal.addEventListener(
            "click",
            function (e) {

                if (
                    e.target === uploadModal
                ) {

                    fecharModal();

                }

            }
        );

    }


    /* =========================================================
       ADESIVOS (STICKERS)
       ========================================================= */

    /*
     * Precisa bater com a lista de emojis permitidos
     * em classes/mural_stickers.php. O servidor sempre
     * revalida, isso aqui é só pra montar o seletor.
     */
    const EMOJIS_ADESIVOS = [
        "❤️", "😍", "👏", "🔥", "😂", "🌟"
    ];

    let popoverAdesivo = null;
    let fotoIdSelecionada = null;


    function criarPopoverAdesivo() {

        const popover =
            document.createElement("div");

        popover.className = "adesivo-popover";

        EMOJIS_ADESIVOS.forEach(function (emoji) {

            const botao =
                document.createElement("button");

            botao.type = "button";
            botao.textContent = emoji;

            botao.addEventListener(
                "click",
                function (e) {

                    e.stopPropagation();

                    if (fotoIdSelecionada) {
                        enviarAdesivo(
                            fotoIdSelecionada,
                            emoji
                        );
                    }

                    fecharPopoverAdesivo();

                }
            );

            popover.appendChild(botao);

        });

        document.body.appendChild(popover);

        return popover;
    }


    function abrirSeletorAdesivo(botao, fotoId) {

        if (!popoverAdesivo) {
            popoverAdesivo = criarPopoverAdesivo();
        }

        fotoIdSelecionada = fotoId;

        const rect =
            botao.getBoundingClientRect();

        popoverAdesivo.style.top =
            `${rect.bottom + 8}px`;

        popoverAdesivo.style.left =
            `${Math.max(8, rect.left - 60)}px`;

        popoverAdesivo.classList.add("ativo");
    }


    function fecharPopoverAdesivo() {

        if (popoverAdesivo) {
            popoverAdesivo.classList.remove("ativo");
        }

        fotoIdSelecionada = null;
    }


    window.abrirSeletorAdesivo = abrirSeletorAdesivo;


    document.addEventListener(
        "click",
        function (e) {

            if (
                popoverAdesivo &&
                popoverAdesivo.classList.contains("ativo") &&
                !popoverAdesivo.contains(e.target) &&
                !e.target.closest(".botao-colar-adesivo")
            ) {

                fecharPopoverAdesivo();

            }

        }
    );


    async function enviarAdesivo(fotoId, emoji) {

        try {

            const resposta =
                await fetch(
                    "sticker.php",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            acao: "adicionar",
                            foto_id: fotoId,
                            emoji: emoji
                        })
                    }
                );

            const data =
                await resposta.json();

            if (data.sucesso) {

                renderizarAdesivo(
                    data.sticker,
                    true
                );

            } else {

                mostrarMensagem(
                    data.mensagem ||
                    "Não foi possível colar o adesivo.",
                    "erro"
                );

            }

        } catch (erro) {

            console.error(
                "Erro ao colar adesivo:",
                erro
            );

            mostrarMensagem(
                "Ocorreu um erro ao colar o adesivo.",
                "erro"
            );

        }

    }


    function renderizarAdesivo(sticker, animar) {

        const card =
            document.querySelector(
                `.polaroid-card[data-foto-id="${sticker.foto_id}"]`
            );

        if (!card) {
            return;
        }

        const container =
            card.querySelector(".polaroid-stickers");

        if (!container) {
            return;
        }

        const elemento =
            document.createElement("div");

        elemento.className =
            "mural-sticker sticker-proprio";

        elemento.dataset.stickerId = sticker.id;
        elemento.textContent = sticker.emoji;

        elemento.style.top =
            `${sticker.posicao_top}%`;

        elemento.style.left =
            `${sticker.posicao_left}%`;

        elemento.title =
            "Clique para remover seu adesivo";

        elemento.addEventListener(
            "click",
            function () {

                removerAdesivo(
                    elemento,
                    sticker.id
                );

            }
        );

        if (animar) {

            elemento.style.opacity = "0";

            elemento.style.transform =
                `rotate(${sticker.rotacao}deg) scale(0)`;

            container.appendChild(elemento);

            requestAnimationFrame(function () {

                elemento.style.opacity = "1";

                elemento.style.transform =
                    `rotate(${sticker.rotacao}deg) scale(1)`;

            });

        } else {

            elemento.style.transform =
                `rotate(${sticker.rotacao}deg)`;

            container.appendChild(elemento);

        }

    }


    async function removerAdesivo(elemento, stickerId) {

        if (!confirm("Remover este adesivo?")) {
            return;
        }

        try {

            const resposta =
                await fetch(
                    "sticker.php",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            acao: "remover",
                            sticker_id: stickerId
                        })
                    }
                );

            const data =
                await resposta.json();

            if (data.sucesso) {

                elemento.style.opacity = "0";

                elemento.style.transform +=
                    " scale(0)";

                setTimeout(
                    function () {
                        elemento.remove();
                    },
                    250
                );

            } else {

                mostrarMensagem(
                    data.mensagem ||
                    "Não foi possível remover o adesivo.",
                    "erro"
                );

            }

        } catch (erro) {

            console.error(
                "Erro ao remover adesivo:",
                erro
            );

            mostrarMensagem(
                "Ocorreu um erro ao remover o adesivo.",
                "erro"
            );

        }

    }


    /*
     * Ativa o clique de remoção nos adesivos que já
     * vieram renderizados pelo servidor no carregamento
     * da página (os do próprio usuário).
     */
    document
        .querySelectorAll(".mural-sticker.sticker-proprio")
        .forEach(function (elemento) {

            elemento.addEventListener(
                "click",
                function () {

                    removerAdesivo(
                        elemento,
                        elemento.dataset.stickerId
                    );

                }
            );

        });

});