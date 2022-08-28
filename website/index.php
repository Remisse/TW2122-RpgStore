<?php
    require_once("bootstrap.php");

    $template_params["title"] = "Home";
    $template_params["template"] = "categories-container.php";
    $template_params["js"] = array(
        "js/common.js",
        "js/aside-sales.js",
        "js/home-cards.js"
    );

    require_once("template/base.php");
?>
