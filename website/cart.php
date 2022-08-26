<?php
    require_once("bootstrap.php");

    $template_params["title"] = "Carrello";
    $template_params["template"] = "cart-content.php";
    $template_params["js"] = array(
        "js/common.js",
        "js/cart-list.js"
    );

    require_once("template/base.php");
?>
