document.addEventListener('DOMContentLoaded', function() {
    const questionCards = document.querySelectorAll('.question-card');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const submitBtn = document.querySelector('.submit-btn');
    const progressText = document.querySelector('.progress-text');
    const resultContainer = document.querySelector('.result-container');
    const scoreElement = document.getElementById('score');
    const restartBtn = document.querySelector('.restart-btn');
    
    let currentQuestion = 0;
    let userAnswers = Array(questionCards.length).fill(null);
    let quizSubmitted = false;

    // Inicializa o quiz
    showQuestion(currentQuestion);

    // Event listeners
    prevBtn.addEventListener('click', showPrevQuestion);
    nextBtn.addEventListener('click', showNextQuestion);
    submitBtn.addEventListener('click', submitQuiz);
    restartBtn.addEventListener('click', restartQuiz);

    // Adiciona event listeners para os botões de opção
    document.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (quizSubmitted) return;
            
            const questionCard = this.closest('.question-card');
            const options = questionCard.querySelectorAll('.option-btn');
            
            // Remove a seleção anterior
            options.forEach(opt => opt.classList.remove('selected'));
            
            // Marca a opção selecionada
            this.classList.add('selected');
            
            // Armazena a resposta do usuário
            const questionIndex = Array.from(questionCards).indexOf(questionCard);
            userAnswers[questionIndex] = this.textContent;
        });
    });

    function showQuestion(index) {
        // Esconde todas as perguntas
        questionCards.forEach(card => card.classList.remove('active'));
        
        // Mostra a pergunta atual
        questionCards[index].classList.add('active');
        
        // Atualiza navegação
        prevBtn.disabled = index === 0;
        nextBtn.disabled = index === questionCards.length - 1;
        progressText.textContent = `${index + 1} de ${questionCards.length}`;

        // Se o quiz foi submetido, mostra as respostas corretas/incorretas
        if (quizSubmitted) {
            const questionCard = questionCards[index];
            const options = questionCard.querySelectorAll('.option-btn');
            const correctAnswer = questionCard.querySelector('[data-correct="true"]').textContent;
            const userAnswer = userAnswers[index];

            options.forEach(opt => {
                opt.classList.remove('correct', 'incorrect');
                
                // Marca a resposta correta
                if (opt.textContent === correctAnswer) {
                    opt.classList.add('correct');
                }
                
                // Marca a resposta do usuário se for incorreta
                if (userAnswer && opt.textContent === userAnswer && userAnswer !== correctAnswer) {
                    opt.classList.add('incorrect');
                }
            });
        }
    }

    function showPrevQuestion() {
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    }

    function showNextQuestion() {
        if (currentQuestion < questionCards.length - 1) {
            currentQuestion++;
            showQuestion(currentQuestion);
        }
    }

    function submitQuiz() {
        if (quizSubmitted) return;
        
        quizSubmitted = true;
        
        // Calcula a pontuação
        let score = 0;
        userAnswers.forEach((answer, index) => {
            const correctAnswer = questionCards[index].querySelector('[data-correct="true"]').textContent;
            if (answer === correctAnswer) {
                score++;
            }
        });
        
        // Atualiza a UI
        submitBtn.textContent = 'Quiz Concluído';
        submitBtn.classList.add('verified');
        scoreElement.textContent = score;
        resultContainer.classList.remove('hidden');
        
        // Mostra as respostas corretas/incorretas
        showQuestion(currentQuestion);
        
        // Rolagem suave para o resultado
        resultContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function restartQuiz() {
        location.reload();
    }
});