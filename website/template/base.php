<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RPG Store - <?php echo $template_params["title"]; ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link href="style/style.css" rel="stylesheet" />
    <?php
    if (isset($template_params["js"])):
        foreach($template_params["js"] as $script):
    ?>
        <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</head>
<body class="bg-light">
    <div class="row bg-dark overflow-hidden">
        <header class="text-bg-dark py-2">
            <h1 class="text-center text-nowrap"><a href="index.php">RPG Store</a></h1>
        </header>
    </div>
    <div class="container-fluid px-0 overflow-visible">
        <div class="row bg-dark d-flex justify-content-between align-items-center">
            <div class="col-6">
                <nav class="navbar navbar-expand-lg navbar-dark py-1">
                    <button type="button" class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="nav > div:first-of-type">
                        <img src="svg/menu.svg" alt="Mostra il menu di navigazione" />
                    </button>
                    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1">
                        <div class="offcanvas-header">
                            <button type="button" class="btn" data-bs-dismiss="offcanvas">
                                <img src="svg/x.svg" alt="Chiudi il menu di navigazione" />
                            </button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
                                <li class="nav-item dropdown">
                                    <button type="button" class="btn nav-link dropdown-toggle" data-bs-toggle="dropdown">Giochi</button>
                                    <!-- Populated via JS. -->
                                </li>
                                <li class="nav-item dropdown">
                                    <button type="button" class="btn nav-link dropdown-toggle" data-bs-toggle="dropdown">Categorie</button>
                                    <!-- Populated via JS. -->
                                </li>
                                <li class="nav-item">
                                    <!-- TODO Link to the user area if already logged in. -->
                                    <a class="nav-link" href="login.php">Accedi</a>
                                </li>
                                <li>
                                    <form title="Cerca nel sito" method="get" action="items.php" class="d-flex ms-2">
                                        <label for="sitesearch" aria-label="Cerca nel sito"></label>
                                        <input type="search" name="search" class="form-control me-2" placeholder="Cerca nel sito" />
                                        <button type="submit" class="btn btn-primary">
                                            <img src="svg/search.svg" alt="Effettua ricerca" />
                                        </button>
                                    </form>
                                </li>
                                <!-- TODO Add a logout button. -->
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-6">
                <nav class="nav justify-content-end flex-nowrap">
                    <a class="nav-link" href="cart.php">
                        <img src="svg/cart.svg" alt="Carrello" />
                        <?php 
                        if (isset($_SESSION["cart"])) {
                            echo "(".countItemsInCart().")";
                        }
                        ?>
                    </a>
                    <a class="nav-link" href="#"><img src="svg/bell.svg" alt="Notifiche" /> (n)</a>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid overflow-scroll">
        <div class="row d-flex justify-content-around align-items-top">
            <div class="col-12 col-md-8">
                <main class="bg-light">
                    <?php
                    if (isset($template_params["template"])) {
                        require($template_params["template"]); 
                    }
                    ?>
                </main>
            </div>
            <div class="col-12 col-md-4">
                <aside class="bg-light border px-3">
                    <section class="pb-3">
                        <h4 class="text-center py-3">Novità</h4>
                        <!-- Populated via JS. -->
                    </section>
                </aside>
                <aside class="bg-light border px-3">
                    <section class="pb-3">
                        <h4 class="text-center py-3">Articoli in sconto</h4>
                        <!-- Populated via JS. -->
                    </section>
                </aside>
            </div>
        </div>
    </div>

    <footer>
        <div class="container-fluid bg-dark py-2">
            <p class="text-bg-dark text-center mb-0">RPG Store - Progetto per Tecnologie Web A.A. 2021/2022</p>
        </div>
    </footer>
</body>
</html>
