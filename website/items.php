<?php
    require_once("bootstrap.php");

    $searchParams = array("search", "categoryid", "brandid");

    foreach ($searchParams as $param) {
        if (isset($_GET[$param]) && $_GET[$param] !== "all") {
            $template_params[$param] = $_GET[$param];
        }
    }

    $template_params["categories"] = $dbh->getCategories();
    $template_params["brands"] = $dbh->getBrands();

    $template_params["title"] = "Articoli";
    $template_params["template"] = "itemgrid.php";
    $template_params["js"] = array(
        "js/common.js",
        "js/aside-sales.js",
        "js/item-grid.js"
    );

    require_once("template/base.php");
?>
