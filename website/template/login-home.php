<div class="text-center">
    <header class="d-flex flex-column align-items-center">
        <h2 class="pt-3">Ciao, <?php echo Session::name(); ?></h2>
        <nav class="py-2">
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item <?php echo ($_SERVER["PHP_SELF"] === "/login.php" ? "active" : ""); ?>">
                    <a class="nav-link text-nowrap" href="login.php">Ordini</a>
                </li>
                <?php if ($template_params["admin"]): ?>
                    <li class="nav-item <?php echo ($_SERVER["PHP_SELF"] === "/adminarea.php" ? "active" : ""); ?>">
                        <a class="nav-link text-nowrap" href="adminarea.php" class="text-warning">Gestione sito</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <section>
        <div class="border rounded">
            <h3 class="py-3">Ordini</h3>
            <ul class="list-group">
                <!-- Populated via JS. -->
            </ul>
        </div>
    </section>
</div>
