<section class="container pb-2">
    <h3 class="text-center py-3">Carrello</h3>
    <div class="border rounded">
        <section>
            <ul class="p-3">
                <!-- Populated via JS. -->
            </ul>
        </section>
        <?php if (Cart::countAll() > 0): ?>
            <section>
                <div class="text-center pb-3">
                    <p>Totale: <strong><?php echo bigintToCurrencyFormat($template_params["totalamount"]); ?></strong></p>
                    <a href="payment.php" class="btn btn-primary">Effettua ordine</a>
                </div>
            </section>
        <?php endif; ?>
    </div>
</section>
