document.addEventListener("DOMContentLoaded", function() {
    let botoes = document.querySelectorAll(".opcao");
    let respostasSelecionadas = new Array(10).fill(null);

    botoes.forEach(botao => {
        botao.addEventListener("click", function() {
            let perguntaDiv = this.closest(".pergunta");
            let index = [...document.querySelectorAll(".pergunta")].indexOf(perguntaDiv);
            respostasSelecionadas[index] = this.innerText;

            perguntaDiv.querySelectorAll(".opcao").forEach(b => b.classList.remove("selecionada"));
            this.classList.add("selecionada");
        });
    });

    window.verificarRespostas = function() {
        let perguntas = document.querySelectorAll(".pergunta");
        let acertos = 0;

        perguntas.forEach((pergunta, i) => {
            let respostaCorreta = pergunta.dataset.resposta;
            let respostaEscolhida = respostasSelecionadas[i];

            pergunta.querySelectorAll(".opcao").forEach(botao => {
                if (botao.innerText === respostaCorreta) {
                    botao.classList.add("correta");
                } else if (botao.innerText === respostaEscolhida) {
                    botao.classList.add("errada");
                }
                botao.disabled = true;
            });

            if (respostaEscolhida === respostaCorreta) {
                acertos++;
            }
        });

        document.getElementById("resultado").innerText = `Você acertou ${acertos} de 10 perguntas!`;
        document.getElementById("verificar").style.display = "none";
        document.getElementById("jogar-novamente").style.display = "block";
    };
});
