document.addEventListener("DOMContentLoaded", function () {
    const stage1 = document.getElementById("stage-1");
    const stage2 = document.getElementById("stage-2");
    const stage3 = document.getElementById("stage-3");
    const finalCard = document.getElementById("final-card");

    function startAnimation() {
        setTimeout(() => {
            stage1.classList.remove("hidden");
            stage1.classList.add("fade-in");

            setTimeout(() => {
                stage1.classList.add("hidden");

                stage2.classList.remove("hidden");
                stage2.classList.add("fade-in");

                setTimeout(() => {
                    stage2.classList.add("hidden");

                    stage3.classList.remove("hidden");
                    stage3.classList.add("fade-in");

                    setTimeout(() => {
                        stage3.classList.add("hidden");

                        finalCard.classList.remove("hidden");
                        finalCard.classList.add("fade-in");
                    }, 2000);
                }, 2000);
            }, 2000);
        }, 1000);
    }

    startAnimation();
});
