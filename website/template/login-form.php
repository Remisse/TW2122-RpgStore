<form action="#" method="POST">
    <div class="text-center py-3">
        <h2>Login</h2>
    </div>
    <section>
        <div class="container-fluid border rounded text-center p-3">
            <?php if (isset($template_params["error"])): ?>
                <div class="row">
                    <p><?php echo $template_params["error"]; ?></p>
                </div>
            <?php endif; ?>
            <div class="row pb-2">
                <div class="col-12 col-md-3">
                    <label for="email" class="form-label">Username:</label>
                </div>
                <div class="col-12 col-md-9">
                    <input type="email" id="email" name="email" class="form-control" required />
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-12 col-md-3">
                    <label for="password" class="form-label">Password:</label>
                </div>
                <div class="col-12 col-md-9">
                    <input type="password" name="password" id="password" class="form-control" required />
                </div>
            </div>
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6">
                    <input type="submit" value="Invia" name="submit" class="btn btn-primary" />
                </div>
                <div class="col-3"></div>
            </div>
        </div>
    </section>
</form>
