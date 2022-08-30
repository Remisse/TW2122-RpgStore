<?php
    require_once("bootstrap.php");

    $template_params["js"] = array("js/common.js");

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $result = $dbh->checkLogin($_POST["email"]);

        if (count($result) == 0 || !password_verify($_POST["password"], $result["password"])) {
            $template_params["error"] = "Verificare che indirizzo e-mail e password siano corretti.";
        } else {
            unset($result["password"]);
            Session::register($result);
            if (isset($_GET["redirect"])) {
                header("location: ".$_GET["redirect"]);
            }
        }
    }

    if (Session::isUserLoggedIn()) {
        $template_params["title"] = "Profilo";
        $template_params["admin"] = $dbh->isUserAdmin(Session::id());
        $template_params["template"] = "login-skeleton.php";
        $template_params["area"] = $_GET["area"] ?? "profile-content";
        $template_params["formmsg"] = $_GET["formmsg"] ?? null;

        switch ($template_params["area"]) {
            case "profile-content":
                $resultNames = array("emailresult", "passwordresult", "billingresult");
                foreach ($resultNames as $resultName) {
                    $template_params[$resultName] = $_GET[$resultName] ?? null;
                }
                $template_params["resultnames"] = $resultNames;

                $template_params["user"] = $dbh->getUserDetails(Session::id());
                break;
            case "orderlist-content":
                $template_params["orders"] = $dbh->getOrdersByUser(Session::id());
                break;
            case "adminorders-content":
                /*
                 * Get every user's orders.
                 * TODO Needs some kind of pagination.
                 */
                $template_params["orders"] = $dbh->getOrders();
                break;
            case "itembin-content":
                $template_params["deleteditems"] = $dbh->getDeletedItems();
                break;
        }
    } else {
        $template_params["title"] = "Accedi";
        $template_params["template"] = "login-form.php";
    }

    require_once("template/base.php");
?>
