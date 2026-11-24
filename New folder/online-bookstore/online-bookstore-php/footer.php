    </main>

    <footer class="bottom-footer mt-auto">
        <div class="container">
            Online Bookstore &mdash; COMP-315 Web Programming &mdash; Jazan University
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updatePriceSum() {
            var items = document.querySelectorAll('.pick-book:checked');
            var sum = 0;
            for (var i = 0; i < items.length; i++) {
                sum += parseFloat(items[i].getAttribute('data-cost') || 0);
            }
            var box = document.getElementById('totalBox');
            if (box) {
                box.textContent = sum.toFixed(2) + ' SAR';
            }
        }
    </script>
</body>
</html>
