<div class="text-center">
    <header class="d-flex flex-column align-items-center">
            <h2 class="pt-3">Profilo</h2>
            <nav class="py-2">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item <?php echo ($_SERVER["PHP_SELF"] === "/login.php" ? "active" : ""); ?>">
                        <a class="nav-link text-nowrap" href="login.php">Ordini</a>
                    </li>
                    <li class="nav-item <?php echo ($_SERVER["PHP_SELF"] === "/adminarea.php" ? "active" : ""); ?>">
                        <a class="nav-link text-nowrap" href="adminarea.php" class="text-warning">Gestione sito</a>
                    </li>
                </ul>
            </nav>
        </header>
    <section>
        <div class="border rounded">
            <h3 class="text-center py-3">Gestione sito</h3>
            <?php if (isset($_GET["formmsg"])): ?>
                <p class="alert alert-primary"><?php echo $_GET["formmsg"]; ?></p>
            <?php endif; ?>
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="itemmanagement.php?action=insert" class="btn btn-outline-secondary">Crea un nuovo articolo</a>
                </li>
            </ul>
        </div>
    </section>
</div>
