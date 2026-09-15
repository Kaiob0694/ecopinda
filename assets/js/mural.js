document.addEventListener("DOMContentLoaded", function () {

    const uploadModal = document.getElementById("uploadModal");
    const fotoInput = document.getElementById("fotoInput");
    const uploadArea = document.getElementById("uploadArea");
    const formUpload = document.getElementById("formUpload");
    const descricao = document.getElementById("descricao");
    const charCount = document.getElementById("charCount");
    const previewContainer = document.getElementById("previewContainer");
    const fotoPreview = document.getElementById("fotoPreview");

    // =========================================================
    // ABRIR MODAL
    // =========================================================

    function abrirModal() {
        if (uploadModal) {
            uploadModal.style.display = "flex";
        }
    }

    // =========================================================
    // FECHAR MODAL
    // =========================================================

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
            charCount.textContent = "0";
        }
    }

    // Disponibiliza globalmente caso o HTML use onclick
    window.abrirModal = abrirModal;
    window.fecharModal = fecharModal;


    // =========================================================
    // CONTADOR DA DESCRIÇÃO
    // =========================================================

    if (descricao && charCount) {

        descricao.addEventListener("input", function () {

            charCount.textContent = this.value.length;

        });

    }


    // =========================================================
    // SELECIONAR FOTO
    // =========================================================

    if (fotoInput) {

        fotoInput.addEventListener("change", function () {

            validarFoto(this.files[0]);

        });

    }


    // =========================================================
    // DRAG AND DROP
    // =========================================================

    if (uploadArea) {

        uploadArea.addEventListener("dragover", function (e) {

            e.preventDefault();

            uploadArea.classList.add("dragover");

        });


        uploadArea.addEventListener("dragleave", function () {

            uploadArea.classList.remove("dragover");

        });


        uploadArea.addEventListener("drop", function (e) {

            e.preventDefault();

            uploadArea.classList.remove("dragover");

            const arquivo = e.dataTransfer.files[0];

            if (arquivo && fotoInput) {

                fotoInput.files = e.dataTransfer.files;

                validarFoto(arquivo);

            }

        });


        uploadArea.addEventListener("click", function () {

            if (fotoInput) {

                fotoInput.click();

            }

        });

    }


    // =========================================================
    // VALIDAR FOTO
    // =========================================================

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

        const tamanhoMaximo = 5 * 1024 * 1024; // 5 MB


        // Verifica formato
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


        // Verifica tamanho
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


        // =====================================================
        // PREVIEW
        // =====================================================

        const reader = new FileReader();

        reader.onload = function (e) {

            if (fotoPreview) {

                fotoPreview.src = e.target.result;

            }

            if (previewContainer) {

                previewContainer.style.display = "block";

            }

        };

        reader.readAsDataURL(arquivo);
    }


    // =========================================================
    // ENVIO DO FORMULÁRIO
    // =========================================================

    if (formUpload) {

        formUpload.addEventListener("submit", async function (e) {

            e.preventDefault();


            // Verifica se existe arquivo
            const arquivo = fotoInput
                ? fotoInput.files[0]
                : null;


            if (!arquivo) {

                mostrarMensagem(
                    "Selecione uma foto antes de publicar.",
                    "erro"
                );

                return;
            }


            // =====================================================
            // FORM DATA
            // =====================================================

            const formData = new FormData();

            formData.append("foto", arquivo);

            formData.append(
                "descricao",
                descricao ? descricao.value : ""
            );


            // =====================================================
            // BOTÃO
            // =====================================================

            const botao = formUpload.querySelector(
                'button[type="submit"]'
            );


            if (botao) {

                botao.disabled = true;

                botao.textContent = "Publicando...";

            }


            // =====================================================
            // ENVIO
            // =====================================================

            try {

                const resposta = await fetch("upload.php", {

                    method: "POST",

                    body: formData

                });


                // Verifica se a resposta HTTP foi válida
                if (!resposta.ok) {

                    throw new Error(
                        `Erro HTTP ${resposta.status}`
                    );

                }


                const data = await resposta.json();


                // =================================================
                // SUCESSO
                // =================================================

                if (data.sucesso) {


                    // Fecha o modal
                    fecharModal();


                    // =================================================
                    // ATUALIZA PINDACOINS NO HEADER
                    // =================================================

                    const elementoCoins =
                        document.getElementById("pindacoins");


                    if (
                        elementoCoins &&
                        data.pindacoins_ganhos
                    ) {

                        // Pega o saldo atual exibido no header
                        const textoAtual =
                            elementoCoins.textContent;


                        const saldoAtual =
                            parseInt(
                                textoAtual.replace(/\D/g, ""),
                                10
                            ) || 0;


                        // Soma as novas moedas
                        const novoSaldo =
                            saldoAtual +
                            parseInt(
                                data.pindacoins_ganhos,
                                10
                            );


                        // Atualiza o header
                        elementoCoins.textContent =
                            `🪙 ${novoSaldo}`;

                    }


                    // =================================================
                    // MENSAGEM DE SUCESSO
                    // =================================================

                    mostrarMensagem(
                        "🎉 Foto publicada com sucesso!",
                        "sucesso"
                    );


                } else {


                    // =================================================
                    // ERRO RETORNADO PELO PHP
                    // =================================================

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


                // =================================================
                // RESTAURA BOTÃO
                // =================================================

                if (botao) {

                    botao.disabled = false;

                    botao.textContent = "Publicar";

                }

            }

        });

    }


    // =========================================================
    // MENSAGEM
    // =========================================================

    function mostrarMensagem(
        mensagem,
        tipo = "sucesso"
    ) {

        // Remove mensagem anterior
        const mensagemAnterior =
            document.querySelector(".mensagem-mural");


        if (mensagemAnterior) {

            mensagemAnterior.remove();

        }


        // Cria mensagem
        const mensagemDiv =
            document.createElement("div");


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


        // =====================================================
        // BOTÃO FECHAR
        // =====================================================

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


        // =====================================================
        // REMOVE AUTOMATICAMENTE
        // =====================================================

        setTimeout(function () {

            if (mensagemDiv.parentElement) {

                mensagemDiv.remove();

            }

        }, 5000);

    }


    // =========================================================
    // FECHAR MODAL CLICANDO FORA
    // =========================================================

    if (uploadModal) {

        uploadModal.addEventListener(
            "click",
            function (e) {

                if (e.target === uploadModal) {

                    fecharModal();

                }

            }
        );

    }

})