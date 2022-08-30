<section class="my-3">
    <h3>Ordini</h3>
    <div class="border rounded bg-body overflow-scroll">
        <table class="table m-0">
            <thead>
                <tr>
                    <th scope="col">Numero ordine</th>
                    <th scope="col">Data</th>
                    <th scope="col">Stato</th>
                    <th scope="col">Totale</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($template_params["orders"] as $order): ?>
                    <tr>
                        <th scope="row">
                            <a href="order.php?id=<?php echo $order["orderid"]; ?>">#<?php echo $order["orderid"]; ?></a>
                        </th>
                        <td><?php echo date('d-m-Y', strtotime($order["creationdate"])); ?></td>
                        <td><?php echo $order["statusdescription"]; ?></td>
                        <td><?php echo bigintToCurrencyFormat($order["totalprice"]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
