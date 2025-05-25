</div> <!-- Close container from header -->
    </main> <!-- Close main-content from header -->

    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Judge Scoring System</h3>
                    <p>A comprehensive scoring platform built with Linux, Apache, MySQL, and PHP.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/scoring_system/public/">Home</a></li>
                        <li><a href="/scoring_system/public/scoreboard.php">Live Scoreboard</a></li>
                        <li><a href="/scoring_system/admin/">Admin Panel</a></li>
                        <li><a href="/scoring_system/judge/">Judge Portal</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>System Status</h4>
                    <div class="status-indicators">
                        <div class="status-item">
                            <span class="status-dot status-online"></span>
                            <span>Database: Online</span>
                        </div>
                        <div class="status-item">
                            <span class="status-dot status-online"></span>
                            <span>System: Active</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Scoring System. Built by <a href="">@nokekip</a></p>
                <p class="tech-stack">
                    <span class="tech-badge">Linux</span>
                    <span class="tech-badge">Apache</span>
                    <span class="tech-badge">MySQL</span>
                    <span class="tech-badge">PHP</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="/scoring_system/assets/js/script.js"></script>
    
    <!-- Auto-refresh for scoreboard (if on scoreboard page) -->
    <?php if (basename($_SERVER['PHP_SELF']) == 'scoreboard.php'): ?>
    <script>
        // Auto-refresh scoreboard every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
    <?php endif; ?>
    
</body>
</html><?php