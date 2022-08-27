<section>
    <div class="text-center py-3">
        <h3>Crea un account</h3>
    </div>
    <section class="container-fluid border rounded">
        <?php if (isset($template_params["error"])): ?>
            <div>
                <p><?php echo $template_params["error"]; ?></p>
            </div>
        <?php endif; ?>
        <form action="signup.php" method="post" title="Registrati">
            <div>
                <label for="name">Nome e cognome</label><input type="text" name="name" id="name" required />
            </div>
            <div>
                <label for="email">Indirizzo e-mail</label><input type="email" name="email" id="email" required />
            </div>
            <div>
                <label for="password">Password (almeno 8 caratteri)</label><input type="password" name="password" id="password" required />
            </div>
            <div>
                <label for="confirmpassword">Conferma password</label><input type="password" name="confirmpassword" id="confirmpassword" required />
            </div>
            <div>
                <input type="submit" value="Registrati" class="btn btn-primary" />
            </div>
        </form>
    </section>
</section>
