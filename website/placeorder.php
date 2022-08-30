<?php
    require_once("bootstrap.php");

    if (!Session::isUserLoggedIn()) {
        header("location: login.php");
    }

    $template_params["title"] = "Ordine";
    $template_params["js"] = array(
        "js/common.js"
    );

    $items = $dbh->getItemPrices(Cart::getRaw());
    addCartQuantityForEachItem($items);
    
    if ($dbh->insertOrder(Session::id(), $items, "È stato effettuato un nuovo ordine.", "Un oggetto non è più disponibile.")) {
        Cart::empty();
        $template_params["template"] = "order-confirmation.php";
    } else {
        $template_params["error"] = "Qualcosa è andato storto. Riprova.";
        $template_params["template"] = "payment-form.php";
    }

    require_once("template/base.php");
?>
