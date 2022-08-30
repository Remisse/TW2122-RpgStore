<section class="my-3">
    <h3>Cestino</h3>
    <?php if (isset($template_params["formmsg"])): ?>
        <p class="alert alert-info"><?php echo $template_params["formmsg"]; ?></p>
    <?php endif; ?>
    <div class="border rounded bg-body overflow-scroll">
        <table class="table m-0">
            <thead>
                <tr>
                    <th scope="col" class="text-nowrap">ID articolo</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Prezzo</th>
                    <th scope="col">Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($template_params["deleteditems"] as $item): ?>
                    <tr>
                        <th scope="row"><?php echo $item["itemid"]; ?></th>
                        <td class="text-start"><?php echo (isset($item["brandshortname"]) ? $item["brandshortname"]." " : "").$item["itemname"]; ?></td>
                        <td><?php echo $item["categoryname"]; ?></td>
                        <td><?php echo bigintToCurrencyFormat($item["itemprice"]); ?></td>
                        <td>
                            <a href="../../itemprocessing.php?action=restore&id=<?php echo $item["itemid"]?>" class="btn btn-outline-secondary p-1">Recupera</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
