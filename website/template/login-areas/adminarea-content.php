<?php if (isset($template_params["admin"]) && $template_params["admin"] === true): ?>
    <section class="py-3">
        <h3>Gestione sito</h3>
        <div>
            <?php if (isset($_GET["formmsg"])): ?>
                <p class="alert alert-primary"><?php echo $_GET["formmsg"]; ?></p>
            <?php endif; ?>
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="itemmanagement.php?action=insert" class="btn btn-primary">Crea un nuovo articolo</a>
                </li>
            </ul>
        </div>
    </section>
<?php endif; ?>
