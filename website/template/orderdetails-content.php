<article>
    <h2 class="text-center py-3">Ordine</h2>
    <?php if (isset($template_params["error"])): ?>
        <p class="alert alert-danger m-3"><?php echo $template_params["error"]; ?></p>
    <?php endif; ?>
    <section class="border rounded bg-body text-center mt-2 p-3 overflow-scroll">
        <h3>Dettagli</h3>
        <table class="table">
            <tr>
                <th scope="col">Effettuato in data</th>
                <th scope="col">Stato</th>
                <th scope="col">Totale</th>
            </tr>
            <tr>
                <td><?php echo date("d-m-Y", strtotime($template_params["order"]["creationdate"])); ?></td>
                <td><?php echo $template_params["order"]["statusdescription"]; ?></td>
                <td><?php echo bigintToCurrencyFormat($template_params["order"]["totalprice"]); ?></td>
            </tr>
        </table>
        <div>
            <h4 class="fs-6 fw-bold">Indirizzo di spedizione</h4>
            <p>Via dell'Università, 50 - 47521 Cesena (FC)</p>
        </div>
    </section>
    <section class="border rounded bg-body mt-2 p-3 overflow-scroll">
        <h3 class="text-center">Articoli acquistati</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Articolo</th>
                    <th scope="col" class="text-center">Quantità</th>
                    <th scope="col" class="text-center">Prezzo unitario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($template_params["items"] as $item): ?>
                    <tr>
                        <th scope="row">
                            <a href="../itemdetails.php?id=<?php echo $item["itemid"]; ?>" class="text-decoration-none">
                                <?php echo (isset($item["brandshortname"]) ? $item["brandshortname"]." " : "").$item["itemname"]; ?>
                            </a>
                        </th>
                        <td class="text-center"><?php echo $item["qty"]; ?></td>
                        <td class="text-center"><?php echo bigintToCurrencyFormat($item["unitprice"]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</article>
<aside class="border rounded bg-body text-center my-3">
    <h3 class="my-2">Azioni</h3>
    <div class="container-fluid py-2">
        <div class="row g-2 text-center">
            <?php if (isset($template_params["admin"]) && $template_params["admin"]): ?>
                <div class="col-12 col-md-6">
                    <a href="../ordermanagement.php?status=shipped&id=<?php echo $template_params["order"]["orderid"]; ?>" class="btn btn-secondary w-100">Segna come spedito</a>
                </div>
            <?php endif; ?>
            <div class="col-12 col-md-6 text-center">
                <a href="../order-confirmdelete.php?id=<?php echo $template_params["order"]["orderid"]; ?>" class="btn btn-danger w-100">Annulla ordine</a>
            </div>
        </div>
    </div>
</aside>
