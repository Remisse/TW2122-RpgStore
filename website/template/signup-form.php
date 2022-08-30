<section>
    <div class="text-center py-3">
        <h3>Crea un account</h3>
    </div>
    <section class="container-fluid border rounded bg-body p-3">
        <?php if (isset($template_params["error"])): ?>
            <div>
                <p><?php echo $template_params["error"]; ?></p>
            </div>
        <?php endif; ?>
        <form action="signup.php" method="post" enctype="multipart/form-data" title="Registrati">
            <div class="row mb-2">
                <div class="col-12 col-md-4">
                    <label for="name" class="form-label">Nome e cognome</label>
                </div>
                <div class="col-12 col-md-8">
                    <input type="text" name="name" id="name" class="form-control" required />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12 col-md-4">
                    <label for="email" class="form-label">Indirizzo e-mail</label>
                </div>
                <div class="col-12 col-md-8">
                    <input type="email" name="email" id="email" class="form-control" required />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12 col-md-4">
                    <label for="password" class="form-label">Password (almeno 8 caratteri)</label>
                </div>
                <div class="col-12 col-md-8">
                    <input type="password" name="password" id="password" class="form-control" required />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12 col-md-4">
                    <label for="confirmpassword" class="form-label">Conferma password</label>
                </div>
                <div class="col-12 col-md-8">
                    <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required />
                </div>
            </div>
            <div class="text-center py-3">
                <input type="submit" value="Registrati" class="btn btn-primary" />
            </div>
        </form>
    </section>
</section>
