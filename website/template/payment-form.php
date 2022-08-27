<section>
    <h3>Effettua l'ordine</h3>
    <?php if (isset($template_params["error"])): ?>
        <p><?php echo $template_params["error"]; ?></p>
    <?php endif;?>
    <form action="placeorder.php" method="post" title="Dettagli dell'ordine">
        <fieldset>
            <legend>Indirizzo di fatturazione</legend>
            <label for="billing">Indirizzo di fatturazione</label>
            <input type="text" name="billing" value="<?php echo $template_params["billingaddress"]; ?>" placeholder="Indirizzo" />
        </fieldset>
        <fieldset>
            <legend>Metodo di pagamento</legend>
            <ul>
                <li>
                    <input type="radio" name="payment" id="paypal" checked /><label for="paypal">PayPal</label>
                </li>
                <li>
                    <input type="radio" name="payment" id="card" /><label for="card">Carta di credito/debito</label>
                </li>
            </ul>
        </fieldset>
        <div>
            <input type="submit" name="submit" value="Effettua ordine" />
        </div>
    </form>
</section>
