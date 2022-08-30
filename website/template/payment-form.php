<section class="mb-3">
    <h2 class="text-center py-3">Effettua l'ordine</h2>
    <?php if (isset($template_params["error"])): ?>
        <p class="alert alert-error"><?php echo $template_params["error"]; ?></p>
    <?php endif;?>
    <ul class="list-group">
        <li class="list-group-item py-3">
            <h4 class="fs-6 fw-normal">Indirizzo di fatturazione</h4>
            <p class="fs-5 m-0"><?php echo $template_params["billingaddress"]; ?></p>
        </li>
        <li class="list-group-item py-3">
            <h4 class="fs-6 fw-normal">Indirizzo di spedizione</h4>
            <p class="fs-5 m-0">Via dell'Università, 50 - 47521 Cesena (FC)</p>
        </li>
        <li class="list-group-item py-3">
            <form action="placeorder.php" method="post" enctype="multipart/form-data" title="Dettagli dell'ordine">
                <fieldset>
                    <div class="mb-3">
                        <legend class="fs-6">Metodo di pagamento</legend>
                        <div class="form-check d-flex flex-row align-items-center">
                            <input type="radio" name="payment" id="paypal" checked class="form-check-input" />
                            <label for="paypal" class="ps-2 fs-5">PayPal</label>
                        </div>
                        <div class="form-check d-flex flex-row align-items-center">
                            <input type="radio" name="payment" id="card" class="form-check-input" />
                            <label for="card" class="ps-2 fs-5">Carta di credito/debito</label>
                        </div>
                    </div>
                </fieldset>
                <div class="text-center">
                    <input type="submit" name="submit" value="Effettua ordine" class="btn btn-success" />
                </div>
            </form>
        </li>
    </ul>
</section>
