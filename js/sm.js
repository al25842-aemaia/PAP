// Mostrar seção selecionada
function showSection(sectionId) {
    try {
        // Esconder todas as seções
        document.querySelectorAll('.content-section').forEach(section => {
            section.style.display = 'none';
            section.classList.remove('active');
        });
        
        // Mostrar a seção selecionada
        const section = document.getElementById(sectionId);
        if (section) {
            section.style.display = 'block';
            section.classList.add('active');
        }
        
        // Atualizar navegação ativa
        document.querySelectorAll('nav ul li a').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-section') === sectionId) {
                link.classList.add('active');
            }
        });
        
        // Se for a seção do plantel, mostrar a aba padrão
        if (sectionId === 'squad') {
            showSquadTab('starting');
        }
    } catch (error) {
        console.error("Error showing section:", error);
    }
}

// Mostrar aba do plantel selecionada
function showSquadTab(tab) {
    try {
        // Esconder todas as abas de conteúdo
        document.querySelectorAll('.squad-content').forEach(content => {
            content.style.display = 'none';
            content.classList.remove('active');
        });
        
        // Mostrar a aba selecionada
        const tabContent = document.getElementById(tab + '-content');
        if (tabContent) {
            tabContent.style.display = 'block';
            tabContent.classList.add('active');
        }
        
        // Atualizar botões das abas
        document.querySelectorAll('.squad-tabs .tab-button').forEach(button => {
            button.classList.remove('active');
            if (button.getAttribute('data-tab') === tab) {
                button.classList.add('active');
            }
        });
    } catch (error) {
        console.error("Error showing squad tab:", error);
    }
}

// Inicializar a página
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Configurar event listeners para navegação principal
        document.querySelectorAll('nav ul li a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const sectionId = this.getAttribute('data-section');
                showSection(sectionId);
                
                // Salvar a seção ativa no sessionStorage
                sessionStorage.setItem('activeSection', sectionId);
            });
        });

        // Configurar event listeners para tabs do plantel
        document.querySelectorAll('.squad-tabs .tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tab = this.getAttribute('data-tab');
                showSquadTab(tab);
                
                // Salvar a aba ativa no sessionStorage
                sessionStorage.setItem('activeSquadTab', tab);
            });
        });

        // Verificar se há uma seção ativa salva
        const savedSection = sessionStorage.getItem('activeSection');
        if (savedSection) {
            showSection(savedSection);
        } else {
            // Mostrar a seção de visão geral por padrão
            showSection('overview');
        }
        
        // Verificar se há uma aba de plantel ativa salva
        if (document.getElementById('squad') && document.getElementById('squad').style.display !== 'none') {
            const savedTab = sessionStorage.getItem('activeSquadTab');
            if (savedTab) {
                showSquadTab(savedTab);
            } else {
                // Mostrar os titulares por padrão
                showSquadTab('starting');
            }
        }
        
    } catch (error) {
        console.error("Initialization error:", error);
    }
});

// Confirmar reset do jogo
document.querySelector('button[name="reset_game"]')?.addEventListener('click', function(e) {
    if (!confirm('Tem certeza que deseja resetar o jogo? Você perderá todo o progresso.')) {
        e.preventDefault();
    }
});