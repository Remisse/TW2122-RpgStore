<?php
    require_once("bootstrap.php");

    if (!Session::isUserLoggedIn() || !isset($_GET["id"]) || !$dbh->canViewOrder(Session::id(), $_GET["id"])) {
        header("location: index.php");
    }
    
    $template_params["template"] = "order-confirmdelete-content.php";
    $template_params["js"] = array("js/common.js");

    require_once("template/base.php");
?>
