<script>
    // Atualiza o ano no copyright automaticamente
    document.getElementById('year').textContent = new Date().getFullYear();
</script>
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-grid">
            <!-- Sobre o site -->
            <div class="footer-column">
                <div class="footer-logo">
                    <span class="logo-icon">⚽</span>
                    <span class="logo-text">Futebol12</span>
                </div>
                <p class="footer-description">O melhor site sobre futebol com notícias atualizadas ao minuto, minijogos viciantes e análises exclusivas.</p>
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Contactos -->
            <div class="footer-column">
                <h3 class="footer-title">Contactos</h3>
                <ul class="contact-list">
                    <li class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <span>al23682@aemaia.com</span>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <span>al25842@aemaia.com</span>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-phone contact-icon"></i>
                        <span>+351 123 456 789</span>
                    </li>
                </ul>
            </div>

            <!-- Links Rápidos -->
            <div class="footer-column">
                <h3 class="footer-title">Links Rápidos</h3>
                <ul class="quick-links">
                    <li><a href="index.php"><i class="fas fa-chevron-right"></i> Página Principal</a></li>
                    <li><a href="minijogos.php"><i class="fas fa-chevron-right"></i> Minijogos</a></li>
                    <li><a href="noticias.php"><i class="fas fa-chevron-right"></i> Notícias</a></li>
                    <li><a href="registro.php"><i class="fas fa-chevron-right"></i> Registo</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="footer-column">
                <h3 class="footer-title">Newsletter</h3>
                <p class="newsletter-text">Subscreva para receber as últimas notícias!</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Seu e-mail" required>
                    <button type="submit" class="subscribe-btn">
                        <i class="fas fa-paper-plane"></i> Subscrever
                    </button>
                </form>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <div class="copyright">
                &copy; <span id="year"></span> Futebol12. Todos os direitos reservados.
            </div>
            <div class="legal-links">
                <a href="#">Termos de Serviço</a>
                <a href="#">Política de Privacidade</a>
                <a href="#">Cookies</a>
            </div>
        </div>
    </div>
</footer>