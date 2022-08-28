<?php
    require_once("bootstrap.php");

    $template_params["template"] = "itemdetails-content.php";
    $template_params["js"] = array("js/common.js");

    if (!$dbh->isUserAdmin(Session::id())) {
        header("location: index.php");
    }

    if (isset($_GET["id"])) {
        $template_params["item"] = $dbh->getItem($_GET["id"]);
    }

    require_once("template/base.php");
?>
