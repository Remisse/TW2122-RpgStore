<?php
    require_once("bootstrap.php");

    if (!Session::isUserLoggedIn() || !isset($_GET["id"]) || !$dbh->canViewOrder(Session::id(), $_GET["id"])) {
        header("location: index.php");
    }

    $errors = array (
        1 => "Non è possibile modificare ulteriormente l'ordine.",
        2 => "Impossibile eseguire la richiesta."
    );
    if (isset($_GET["error"])) {
        $template_params["error"] = $errors[(int)$_GET["error"]] ?? null;
    }

    $template_params["order"] = $dbh->getOrder($_GET["id"]);
    $template_params["items"] = $dbh->getItemsByOrder($_GET["id"]);
    
    $template_params["template"] = "orderdetails-content.php";
    $template_params["js"] = array("js/common.js");

    $template_params["admin"] = $dbh->isUserAdmin(Session::id());

    require_once("template/base.php");
?>
