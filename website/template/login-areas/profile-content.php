<section class="my-3">
    <h3>Modifica il tuo profilo</h3>
    <?php
    $resultNames = $template_params["resultnames"];
    foreach ($resultNames as $resultName):
        if (isset($template_params[$resultName])):
    ?>
        <p class="alert alert-warning py-2"><?php echo $template_params[$resultName]; ?></p>
    <?php 
        endif;
    endforeach;
    ?>
    <div class="border rounded bg-body p-3">
        <form action="profileedit.php" method="post" enctype="multipart/form-data">
            <div class="container-fluid">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <div class="p-1">
                            <label for="email" class="form-label">Indirizzo e-mail</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?php echo $template_params["user"]["email"]; ?>" />
                        </div>
                        <div class="mt-2 p-1">
                            <label for="billingaddress" class="form-label">Indirizzo di fatturazione</label>
                            <input type="text" name="billingaddress" id="billingaddress" class="form-control" value="<?php echo $template_params["user"]["billingaddress"] ?? ""; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-1">
                            <fieldset>
                                <legend class="fs-6 fw-semibold">Modifica la tua password</legend>
                                <div class="pt-2">
                                    <label for="oldpassword">Vecchia password</label>
                                    <input type="password" name="oldpassword" id="oldpassword" class="form-control" />
                                </div>
                                <div class="pt-2">
                                    <label for="newpassword">Nuova password</label>
                                    <input type="password" name="newpassword" id="newpassword" class="form-control" />
                                </div>
                                <div class="pt-2">
                                    <label for="confirmpassword">Conferma nuova password</label>
                                    <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" />
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div>
                    <input type="submit" value="Conferma" class="btn btn-primary mt-3" />
                </div>
            </div>
        </form>
    </div>
</section>
