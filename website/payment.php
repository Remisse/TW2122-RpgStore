<?php
    require_once("bootstrap.php");

    if (!Session::isUserLoggedIn()) {
        header("location: login.php");
    }

    $template_params["title"] = "Dettagli dell'ordine";
    $template_params["template"] = "payment-form.php";
    $template_params["js"] = array("js/common.js");

    $user_details = $dbh->getUserDetails(Session::id());
    if (isset($user_details["billingaddress"])) {
        $template_params["billingaddress"] = $user_details["billingaddress"];
    }

    require_once("template/base.php");
?>
