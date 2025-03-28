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

    showQuestion(currentQuestion);

    // Event listeners
    prevBtn.addEventListener('click', showPrevQuestion);
    nextBtn.addEventListener('click', showNextQuestion);
    submitBtn.addEventListener('click', submitQuiz);
    restartBtn.addEventListener('click', restartQuiz);

    // Selecionar opção de resposta
    document.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (quizSubmitted) return;
            
            const questionCard = this.closest('.question-card');
            const options = questionCard.querySelectorAll('.option-btn');
            
            options.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            const questionIndex = Array.from(questionCards).indexOf(questionCard);
            userAnswers[questionIndex] = this.textContent;
        });
    });

    function showQuestion(index) {
        questionCards.forEach(card => card.classList.remove('active'));
        questionCards[index].classList.add('active');
        
        prevBtn.disabled = index === 0;
        nextBtn.disabled = index === questionCards.length - 1;
        progressText.textContent = `${index + 1} de ${questionCards.length}`;

        if (quizSubmitted) {
            const questionCard = questionCards[index];
            const options = questionCard.querySelectorAll('.option-btn');
            const correctAnswer = questionCard.querySelector('[data-correct="true"]').textContent;
            const userAnswer = userAnswers[index];

            options.forEach(opt => {
                opt.classList.remove('correct', 'incorrect', 'selected');
                
                if (opt.textContent === correctAnswer) {
                    opt.classList.add('correct'); // Resposta correta sempre verde
                }
                
                if (opt.textContent === userAnswer) {
                    if (userAnswer === correctAnswer) {
                        opt.classList.add('correct'); // Acerto do usuário
                    } else {
                        opt.classList.add('incorrect'); // Erro do usuário
                    }
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
        submitBtn.textContent = 'Quiz Concluído';
        submitBtn.style.backgroundColor = '#6c757d';
        
        let score = userAnswers.reduce((acc, answer, index) => {
            const correctAnswer = questionCards[index].querySelector('[data-correct="true"]').textContent;
            return acc + (answer === correctAnswer ? 1 : 0);
        }, 0);
        
        scoreElement.textContent = score;
        resultContainer.classList.remove('hidden');
        showQuestion(currentQuestion);
        resultContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function restartQuiz() {
        location.reload();
    }
});