<?php
    require_once("bootstrap.php");

    $template_params["js"] = array("js/common.js");

    if (!Session::isUserLoggedIn()) {
        header("location: login.php");
    }
    if (!isset($_GET["id"]) || !$dbh->canViewOrder(Session::id(), $_GET["id"])) {
        header("location: index.php");
    }

    // Cancelled orders cannot be updated.
    $error = 0;
    if ($dbh->getOrder($_GET["id"])["statusid"] == 3) {
        $error = 1;
    } else {
        $isAdmin = $dbh->isUserAdmin(Session::id());
        if (!$isAdmin && $_GET["status"] === "cancelled") {
            $dbh->updateOrderStatusAndNotifyAdmins(Session::id(), $_GET["id"], 3, "Un utente ha annullato un proprio ordine.");
        } else if ($isAdmin) {
            switch ($_GET["status"] ?? "") {
                case "shipped":
                    $dbh->updateOrderStatusAndNotifyUser($_GET["id"], 2, "Un ordine è stato spedito.");
                    break;
                case "cancelled":
                    $dbh->updateOrderStatusAndNotifyUser($_GET["id"], 3, "Un ordine è stato annullato.");
                    break;
            } 
        } else {
            header("location: index.php");
        }
    }

    header("location: order.php?id=".$_GET["id"]."&error=".$error);
?>
