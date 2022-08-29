<div class="container py-3">
    <section>
        <h2 class="text-center">Carrello</h2>
        <ul class="p-3">
            <!-- Populated via JS. -->
        </ul>
    </section>
    <?php if (Cart::countAll() > 0): ?>
        <footer class="card mx-4">
            <div class="card-body text-center pb-3">
                <p class="lead">Totale: <strong><?php echo bigintToCurrencyFormat($template_params["totalamount"]); ?></strong></p>
                <a href="payment.php" class="btn btn-primary">Procedi al pagamento</a>
            </div>
        </footer>
    <?php endif; ?>
</div>
