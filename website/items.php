<?php
    require_once("bootstrap.php");

    $template_params["title"] = "Articoli";
    $template_params["template"] = "itemgrid.php";
    $template_params["js"] = array(
        "js/common.js",
        "js/item-grid.js"
    );

    require_once("template/base.php");
?>
