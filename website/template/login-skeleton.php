<div class="text-center">
    <header class="d-flex flex-column align-items-center">
        <h2 class="pt-3">Ciao, <?php echo Session::name(); ?></h2>
        <nav>
            <div class="border rounded bg-body">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($template_params["area"] === "profile-content" ? "active" : ""); ?> text-nowrap" href="login.php?area=profile-content">Il tuo profilo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($template_params["area"] === "orderlist-content" ? "active" : ""); ?> text-nowrap" href="login.php?area=orderlist-content">I tuoi ordini</a>
                    </li>
                    <?php if ($template_params["admin"]): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($template_params["area"] === "adminarea-content" ? "active" : ""); ?> text-nowrap" href="login.php?area=adminarea-content">Gestione sito</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($template_params["area"] === "adminorders-content" ? "active" : ""); ?> text-nowrap" href="login.php?area=adminorders-content">Ordini degli utenti</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($template_params["area"] === "itembin-content" ? "active" : ""); ?> text-nowrap" href="login.php?area=itembin-content">Cestino</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <?php
    if (isset($template_params["area"])) {
        require("login-areas/".$template_params["area"].".php");
    }
    ?>
</div>
