<?php
    require_once("bootstrap.php");

    $template_params["js"] = array("js/common.js");

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $result = $dbh->checkLogin($_POST["email"]);

        if ($result == false || !password_verify($_POST["password"], $result["password"])) {
            $template_params["error"] = "Verificare che indirizzo e-mail e password siano corretti.";
        } else {
            Session::registerLoggedUser($result);
        }
    }

    if (Session::isUserLoggedIn()) {
        $template_params["title"] = "Profilo";
        $template_params["template"] = "login-home.php";
    } else {
        $template_params["title"] = "Accedi";
        $template_params["template"] = "login-form.php";
    }

    require_once("template/base.php");
?>
