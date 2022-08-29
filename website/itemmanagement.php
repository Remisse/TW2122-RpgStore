<?php
    require_once("bootstrap.php");

    $template_params["js"] = array("js/common.js");

    if (!Session::isUserLoggedIn()) {
        header("location: login.php");
    }
    if (!$dbh->isUserAdmin(Session::id()) || !isset($_GET["action"])) {
        header("location: index.php");
    }

    switch ($_GET["action"]) {
        case "insert":
            $template_params["title"] = "Creazione articolo";
            break;
        case "update":
            $template_params["title"] = "Modifica articolo";
            break;
        case "delete":
            $template_params["title"] = "Eliminazione articolo";
            break;
        default:
            header("location: index.php");
            break;
    }

    // Create an empty item
    $template_params["item"] = array_fill_keys($dbh->getItemColumnNames(), "");
    if (($_GET["action"] === "update" || $_GET["action"] === "delete")) {
        $template_params["item"] = $dbh->getItem($_GET["id"] ?? -1);
        if (!isset($template_params["item"])) {
            $template_params["error"] = "Articolo non trovato";
        }
    }

    $template_params["action"] = $_GET["action"];
    $template_params["categories"] = $dbh->getCategories();
    $template_params["brands"] = $dbh->getBrands();

    $template_params["template"] = "itemmanagement-form.php";

    require_once("template/base.php");
?>
