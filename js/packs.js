function abrirPack() {
    const packImage = document.getElementById("pack-image");
    const packStatus = document.getElementById("pack-status");
    const packAnimation = document.getElementById("pack-animation");
    const nacionalidadeImg = document.getElementById("nacionalidade-img");
    const posicaoText = document.getElementById("posicao-text");
    const clubeImg = document.getElementById("clube-img");
    const jogadorImg = document.getElementById("jogador-img");
    const jogadorInfo = document.getElementById("jogador-info");

    // Esconder a imagem do pack ao clicar
    packImage.style.display = "none";
    packStatus.innerText = "Abrindo o Pack...";
    
    // Mostrar a animação gradualmente
    packAnimation.classList.remove("hidden");

    setTimeout(() => {
        nacionalidadeImg.style.opacity = "1";
        nacionalidadeImg.classList.remove("hidden");
    }, 1000);

    setTimeout(() => {
        posicaoText.style.opacity = "1";
        posicaoText.classList.remove("hidden");
    }, 2000);

    setTimeout(() => {
        clubeImg.style.opacity = "1";
        clubeImg.classList.remove("hidden");
    }, 3000);

    setTimeout(() => {
        jogadorImg.style.opacity = "1";
        jogadorImg.classList.remove("hidden");
    }, 4000);

    setTimeout(() => {
        packStatus.innerText = "Pack Aberto!";
        jogadorInfo.classList.remove("hidden");
    }, 5000);
}
