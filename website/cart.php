<?php
    require_once("bootstrap.php");

    $template_params["title"] = "Carrello";
    $template_params["template"] = "cart-content.php";
    $template_params["js"] = array(
        "js/common.js",
        "js/cart-list.js"
    );
    
    $template_params["totalamount"] = 0;
    foreach ($dbh->getItemPrices(Cart::getRaw()) as $item) {
        $template_params["totalamount"] += $item["amount"] * Cart::count($item["itemid"]);
    }

    require_once("template/base.php");
?>
