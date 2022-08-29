<?php
    require_once("bootstrap.php");

    if (!Session::isUserLoggedIn() || !$dbh->isUserAdmin(Session::id())) {
        header("location: index.php");
    }

    $template_params["title"] = "Gestione sito";
    $template_params["template"] = "adminarea-content.php";

    $template_params["js"] = array("js/common.js");

    require_once("template/base.php");
?>
