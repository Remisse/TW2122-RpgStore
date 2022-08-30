<?php
    require_once("bootstrap.php");

    $item_name = $dbh->getItemName($_GET["id"] ?? -1);
    if ($item_name != null) {
        $template_params["title"] = $item_name;
    } else {
        $template_params["title"] = "Articolo non trovato";
    }
    
    $template_params["template"] = "itemdetails-content.php";
    $template_params["js"] = array(
        "js/common.js", 
        "js/aside-sales.js",
        "js/itemdetails.js"
    );

    if (Session::isUserLoggedIn() && $dbh->isUserAdmin(Session::id())) {
        $template_params["admin"] = true;
        $template_params["itemid"] = $_GET["id"];
    }

    require_once("template/base.php");
?>
