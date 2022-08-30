<div class="text-center mb-3">
    <h2 class="py-3"><?php echo $template_params["title"]; ?></h2>
    <?php if (isset($template_params["error"])): ?>
        <p><?php echo $template_params["error"]; ?></p>
    <?php else:
            $item = $template_params["item"];
    ?>
        <?php if (isset($template_params["alert"])): ?>
            <p class="alert alert-danger" role="alert"><?php echo $template_params["alert"]; ?></p>
        <?php endif; ?>
        <form action="itemprocessing.php" method="post" enctype="multipart/form-data" id="itemform">
            <ul class="list-group">
                <li class="list-group-item px-5">
                    <label for="itembrand">Gioco (facoltativo)</label>
                    <select name="itembrand" class="form-select" size="7" id="itembrand" form="itemform">
                        <?php foreach($template_params["brands"] as $brand): 
                            $selected = ($_POST["itembrand"] ?? $item["itembrand"] ?? -1) == $brand["brandid"] ? "selected" : "";
                        ?>
                            <option <?php echo $selected; ?> value="<?php echo $brand["brandid"]; ?>"><?php echo $brand["brandname"]; ?></option>
                        <?php endforeach; ?>                            
                    </select>
                    <!-- Add option to insert new brands. -->
                </li>
                <li class="list-group-item px-5">
                    <label for="itemname">Nome</label>
                    <input type="text" class="form-control" name="itemname" id="itemname" value="<?php echo ($_POST["itemname"] ?? $item["itemname"]); ?>" required />
                </li>
                <li class="list-group-item px-5">
                    <label for="itemdescription">Descrizione</label>
                    <textarea name="itemdescription" class="form-control" id="itemdescription" required><?php echo ($_POST["itemdescription"] ?? $item["itemdescription"]); ?></textarea>
                </li>
                <li class="list-group-item px-5">
                    <label for="itemimg">Immagine</label>
                    <input type="file" class="form-control" name="itemimg" id="itemimg" />
                    <img id="itemimgpreview" src="<?php echo UPLOAD_DIR.$item["itemimg"]; ?>" alt="Immagine dell'articolo" class="img-fluid pt-2" />
                </li>
                <li class="list-group-item px-5">
                    <label for="itemprice">Prezzo (€)</label>
                    <input type="number" class="form-control" name="itemprice" id="itemprice" step=".01" value="<?php echo ($_POST["itemprice"] ?? bigintToHTMLDecimal($item["itemprice"])); ?>" required />
                </li>
                <li class="list-group-item px-5">
                    <label for="itemdiscount">Sconto (%)</label>
                    <input type="number" class="form-control" name="itemdiscount" id="itemdiscount" min="0" max="100" value="<?php echo ($_POST["itemdiscount"] ?? intval($item["itemdiscount"]) * 100); ?>" required />
                </li>
                <li class="list-group-item px-5">
                    <label for="itemstock">Disponibilità</label>
                    <input type="number" class="form-control" name="itemstock" id="itemstock" value="<?php echo ($_POST["itemstock"] ?? $item["itemstock"]); ?>" required />
                </li>
                <li class="list-group-item px-5">
                    <label for="itemcategory">Categoria</label>
                    <select name="itemcategory" class="form-select" size="7" id="itemcategory" form="itemform" required>
                        <?php foreach($template_params["categories"] as $category):
                            $selected = ($_POST["itemcategory"] ?? $item["itemcategory"] ?? -1) == $category["categoryid"] ? "selected" : "";
                        ?>
                            <option <?php echo $selected; ?> value="<?php echo $category["categoryid"]; ?>"><?php echo $category["categoryname"]; ?></option>
                        <?php endforeach; ?>                            
                    </select>
                </li>
                <li class="list-group-item px-5">
                    <label for="itemcreator">Produttore</label>
                    <input type="text" class="form-control" name="itemcreator" id="itemcreator" value="<?php echo ($_POST["itemcreator"] ?? $item["itemcreator"]); ?>" required />
                </li>
                <li class="list-group-item px-5">
                    <label for="itempublisher">Editore (facoltativo)</label>
                    <input type="text" class="form-control" name="itempublisher" id="itempublisher" value="<?php echo ($_POST["itempublisher"] ?? $item["itempublisher"] ?? ""); ?>" />
                </li>
                <li class="list-group-item px-5">
                    <?php if ($template_params["action"] === "delete"): ?>
                        <div class="alert alert-danger">
                            <p class="font-weight-bold">Una volta eliminato, l'articolo non sarà più visibile al pubblico. Tuttavia, sarà possibile recuperarlo accedendo alla sezione 'Articoli eliminati'.</p>
                            <p class="font-weight-bold">Vuoi davvero continuare?</p>
                        </div>
                    <?php endif; ?>
                    <input type="submit" class="btn btn-primary" value="Conferma" />
                </li>
            </ul>

            <?php if ($template_params["action"] === "update" || $template_params["action"] === "delete"): ?>
                <input type="hidden" name="itemid" value="<?php echo $item["itemid"]; ?>" />
            <?php endif; ?>
            <input type="hidden" name="action" id="action" value="<?php echo $template_params["action"]; ?>" />
        </form>
    <?php endif; ?>
</div>
