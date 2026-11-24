    </main>

    <!-- Footer -->
    <footer class="site-footer mt-auto">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                <strong>COMP-315</strong> <span class="footer-sep">|</span> Online Bookstore
            </div>
            <div>
                Jazan University
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function calcTotal() {
            var checks = document.querySelectorAll('.book-check:checked');
            var total = 0;
            for (var i = 0; i < checks.length; i++) {
                total += parseFloat(checks[i].getAttribute('data-price') || 0);
            }
            var el = document.getElementById('selectedTotal');
            if (el) {
                el.textContent = total.toFixed(2) + ' SAR';
            }
        }
    </script>
</body>
</html>
