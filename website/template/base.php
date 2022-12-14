<!DOCTYPE html>
<html lang="it" class="h-100">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RPG Store - <?php echo $template_params["title"]; ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />
    <link href="style/palette.css" rel="stylesheet" />
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
<body class="d-flex flex-column h-100 bg-light">
    <header>
        <h1 class="text-center bg-dark text-nowrap m-0"><a href="index.php">RPG Store</a></h1>
        <div class="container-fluid px-0 overflow-visible">
            <div class="row bg-dark d-flex align-items-center py-1">
                <div class="col-8">
                    <nav class="navbar navbar-expand-lg navbar-dark ps-2 py-0">
                        <button type="button" class="navbar-toggler p-2" data-bs-toggle="offcanvas" data-bs-target="nav > div:first-of-type">
                            <img src="svg/menu.svg" alt="Mostra il menu di navigazione" />
                        </button>
                        <div class="offcanvas offcanvas-start text-bg-dark" role="dialog" aria-label="Menu di navigazione" tabindex="-1">
                            <div class="offcanvas-header">
                                <button type="button" class="btn" data-bs-dismiss="offcanvas">
                                    <img src="svg/x.svg" alt="Chiudi il menu di navigazione" />
                                </button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
                                    <li class="nav-item dropdown">
                                        <button type="button" class="btn nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Giochi</button>
                                        <!-- Populated via JS. -->
                                    </li>
                                    <li class="nav-item dropdown">
                                        <button type="button" class="btn nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Categorie</button>
                                        <!-- Populated via JS. -->
                                    </li>
                                    <?php if (!Session::isUserLoggedIn()): ?>
                                            <li class="nav-item">
                                                <a class="nav-link" href="login.php">Accedi</a>
                                            </li>
                                            <li class="nav-item me-2">
                                                <a class="nav-link" href="signup.php">Registrati</a>
                                            </li>
                                    <?php else: ?>
                                            <li class="nav-item">
                                                <a class="nav-link" href="login.php">Profilo</a>
                                            </li>
                                            <li class="nav-item me-2">
                                                <a class="nav-link" href="logout.php">Esci</a>
                                            </li>
                                    <?php endif; ?>
                                    <li>
                                        <form action="items.php" method="get" role="search" class="ms-2">
                                            <ul class="list-group list-group-horizontal">
                                                <li class="list-group-item p-0 bg-dark d-flex">
                                                    <label for="search" class="text-light me-1">Cerca nel sito</label>
                                                    <input type="search" name="search" id="search" class="form-control me-2" value="<?php echo $_GET["search"] ?? ""; ?>" />
                                                </li>
                                                <li class="list-group-item p-0 bg-dark">
                                                    <button type="submit" class="btn btn-secondary">
                                                        <img src="svg/search.svg" alt="Effettua ricerca" />
                                                    </button>
                                                </li>
                                            </ul>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-4">
                    <nav class="nav justify-content-end flex-nowrap">
                        <a class="nav-link text-nowrap" href="cart.php" role="button" aria-live="polite">
                            <img src="svg/cart.svg" alt="Carrello" />
                            <span class="font-monospace">
                                <?php
                                if ($cartCount > 0) {
                                    echo "(".$cartCount.")";
                                }
                                ?>
                            </span>
                        </a>
                        <div class="dropdown" id="notif-dropdown">
                            <a class="nav-link dropdown-toggle text-nowrap" href="#" role="button" aria-live="polite" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <img src="svg/bell.svg" alt="Notifiche" />
                                <span class="font-monospace">
                                    <?php 
                                    if ($notificationsCount > 0) {
                                        echo "(".$notificationsCount.")";
                                    }
                                    ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- Populated via JS. -->
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid flex-shrink-0">
        <div class="row d-flex justify-content-around">
            <div class="col-12 col-md-8">
                <main class="bg-light px-3">
                    <?php
                    if (isset($template_params["template"])) {
                        require($template_params["template"]); 
                    }
                    ?>
                </main>
            </div>
            <div>
                <!-- Reserved for asides. Set class to "col-12 col-md-4" via JS. -->
            </div>
        </div>
    </div>
    <footer class="mt-auto">
        <div class="container-fluid bg-dark py-2">
            <p class="text-bg-dark text-center mb-0">RPG Store - Progetto per Tecnologie Web A.A. 2021/2022</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>
