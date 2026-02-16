</main>
</div>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-5">
        <div class="container">
            <p class="text-muted mb-0">
                &copy; 2026 BNGRC - Bureau National de Gestion des Risques et Catastrophes
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Confirmation pour les actions destructives
        function confirmAction(message) {
            return confirm(message || 'Êtes-vous sûr de vouloir continuer?');
        }
        
        // Formatage des nombres
        function formatNumber(num) {
            return new Intl.NumberFormat('fr-MG').format(num);
        }
        
        // Formatage des montants
        function formatCurrency(amount) {
            return new Intl.NumberFormat('fr-MG', {
                style: 'currency',
                currency: 'MGA'
            }).format(amount);
        }
    </script>
</body>
</html>
