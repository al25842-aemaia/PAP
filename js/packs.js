document.addEventListener("DOMContentLoaded", function() {
    const stages = [
        document.getElementById("stage-1"),
        document.getElementById("stage-2"),
        document.getElementById("stage-3")
    ];
    const finalCard = document.getElementById("final-card");
    const packStatus = document.getElementById("pack-status");
    const statusMessages = [
        "Analisando nacionalidade...",
        "Identificando posição...",
        "Verificando clube...",
        "Revelando jogador!"
    ];

    // Criar partículas de fundo
    createParticles();

    // Iniciar animação
    setTimeout(startAnimation, 1000);

    function startAnimation() {
        // Animação dos estágios
        stages.forEach((stage, index) => {
            setTimeout(() => {
                // Atualizar mensagem de status
                packStatus.textContent = statusMessages[index];
                
                // Resetar todos os estágios
                stages.forEach(s => {
                    s.classList.remove('active');
                    s.classList.add('hidden');
                });
                
                // Mostrar estágio atual
                stage.classList.remove('hidden');
                setTimeout(() => stage.classList.add('active'), 50);
                
                // Efeito especial no último estágio
                if (index === stages.length - 1) {
                    setTimeout(() => {
                        createFireworks();
                        stages.forEach(s => s.classList.remove('active'));
                        revealFinalCard();
                    }, 2000);
                }
            }, index * 2000);
        });
    }

    function revealFinalCard() {
        packStatus.textContent = statusMessages[3];
        setTimeout(() => {
            finalCard.classList.remove('hidden');
            setTimeout(() => finalCard.classList.add('active'), 50);
            
            // Efeito de confetti
            createConfetti();
        }, 1000);
    }

    function createParticles() {
        const colors = ['#ffcc00', '#ff6b6b', '#48dbfb', '#1dd1a1'];
        const container = document.getElementById('animation-container');
        
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            particle.style.width = Math.random() * 8 + 2 + 'px';
            particle.style.height = particle.style.width;
            particle.style.left = Math.random() * 100 + 'vw';
            particle.style.top = Math.random() * 100 + 'vh';
            particle.style.opacity = Math.random() * 0.5 + 0.1;
            particle.style.borderRadius = '50%';
            particle.style.position = 'absolute';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '1';
            
            container.appendChild(particle);
            
            // Animação flutuante
            animateParticle(particle);
        }
    }

    function animateParticle(particle) {
        const duration = Math.random() * 20 + 10;
        const delay = Math.random() * 5;
        
        particle.animate([
            { transform: 'translate(0, 0) scale(1)', opacity: particle.style.opacity },
            { transform: `translate(${Math.random() * 200 - 100}px, ${Math.random() * 200 - 100}px) scale(${Math.random() * 0.5 + 0.5})`, opacity: 0 }
        ], {
            duration: duration * 1000,
            delay: delay * 1000,
            iterations: Infinity,
            easing: 'ease-in-out'
        });
    }

    function createConfetti() {
        const colors = ['#ffcc00', '#ff6b6b', '#48dbfb', '#1dd1a1', '#f368e0', '#ff9f43'];
        const container = document.getElementById('animation-container');
        
        for (let i = 0; i < 150; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'particle';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 12 + 3 + 'px';
                confetti.style.height = Math.random() * 6 + 3 + 'px';
                confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = -10 + 'px';
                confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                confetti.style.opacity = Math.random() * 0.7 + 0.3;
                confetti.style.position = 'absolute';
                confetti.style.pointerEvents = 'none';
                confetti.style.zIndex = '10';
                
                container.appendChild(confetti);
                
                const animationDuration = Math.random() * 3 + 2;
                
                confetti.animate([
                    { top: '-10px', opacity: 1, transform: `${confetti.style.transform} scale(1)` },
                    { top: '100vh', opacity: 0, transform: `${confetti.style.transform} scale(0.5)` }
                ], {
                    duration: animationDuration * 1000,
                    easing: 'cubic-bezier(0.1, 0.8, 0.9, 1)',
                    delay: Math.random() * 1000
                });
                
                // Remover após animação
                setTimeout(() => confetti.remove(), animationDuration * 1000);
            }, i * 50);
        }
    }

    function createFireworks() {
        const container = document.getElementById('animation-container');
        const colors = ['#ffcc00', '#ff6b6b', '#48dbfb', '#1dd1a1'];
        
        for (let i = 0; i < 12; i++) {
            setTimeout(() => {
                const x = Math.random() * 80 + 10;
                const y = Math.random() * 50 + 25;
                const color = colors[Math.floor(Math.random() * colors.length)];
                
                createFirework(x, y, color, container);
            }, i * 250);
        }
    }

    function createFirework(x, y, color, container) {
        const particles = 50;
        const firework = document.createElement('div');
        firework.style.position = 'absolute';
        firework.style.left = `${x}%`;
        firework.style.top = `${y}%`;
        firework.style.width = '8px';
        firework.style.height = '8px';
        firework.style.borderRadius = '50%';
        firework.style.backgroundColor = color;
        firework.style.boxShadow = `0 0 15px ${color}`;
        firework.style.zIndex = '5';
        firework.style.transform = 'scale(0)';
        
        container.appendChild(firework);
        
        // Animação de explosão
        firework.animate([
            { transform: 'scale(0)', opacity: 0 },
            { transform: 'scale(1)', opacity: 1 },
            { transform: 'scale(0)', opacity: 0 }
        ], {
            duration: 800,
            easing: 'cubic-bezier(0.1, 0.8, 0.2, 1)'
        });
        
        // Criar partículas da explosão
        for (let i = 0; i < particles; i++) {
            setTimeout(() => {
                const particle = document.createElement('div');
                particle.style.position = 'absolute';
                particle.style.left = `${x}%`;
                particle.style.top = `${y}%`;
                particle.style.width = '6px';
                particle.style.height = '6px';
                particle.style.borderRadius = '50%';
                particle.style.backgroundColor = color;
                particle.style.opacity = '0';
                particle.style.zIndex = '5';
                
                container.appendChild(particle);
                
                const angle = Math.random() * Math.PI * 2;
                const distance = Math.random() * 100 + 50;
                const duration = Math.random() * 1500 + 500;
                const size = Math.random() * 0.8 + 0.2;
                
                particle.animate([
                    { 
                        transform: 'translate(0, 0) scale(1)',
                        opacity: 1 
                    },
                    { 
                        transform: `translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px) scale(${size})`,
                        opacity: 0 
                    }
                ], {
                    duration: duration,
                    easing: 'cubic-bezier(0.1, 0.8, 0.2, 1)'
                });
                
                setTimeout(() => particle.remove(), duration);
            }, 400);
        }
        
        setTimeout(() => firework.remove(), 1000);
    }
});