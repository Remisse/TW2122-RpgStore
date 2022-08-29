<section class="mb-3">
    <h3 class="text-center py-3">Effettua l'ordine</h3>
    <?php if (isset($template_params["error"])): ?>
        <p class="alert alert-error"><?php echo $template_params["error"]; ?></p>
    <?php endif;?>
    <form action="placeorder.php" method="post" title="Dettagli dell'ordine">
        <div class="card p-3">
            <fieldset>
                <div class="mb-3">
                    <legend class="form-label">Indirizzo di fatturazione</legend>
                    <label for="billing" class="d-none">Indirizzo di fatturazione</label>
                    <input type="text" name="billing" value="<?php echo $template_params["billingaddress"]; ?>" placeholder="Indirizzo" class="form-control" />
                </div>
            </fieldset>
            <fieldset>
                <div class="mb-3">
                    <legend class="form-label">Metodo di pagamento</legend>
                    <div class="form-check">
                        <input type="radio" name="payment" id="paypal" checked class="form-check-input" />
                        <label for="paypal" class="form-check-label">PayPal</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="payment" id="card" class="form-check-input" />
                        <label for="card" class="form-check-label">Carta di credito/debito</label>
                    </div>
                </div>
            </fieldset>
            <div class="text-center">
                <input type="submit" name="submit" value="Effettua ordine" class="btn btn-success" />
            </div>
        </div>

    </form>
</section>
