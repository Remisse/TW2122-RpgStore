<?php
    require_once("bootstrap.php");

    $template_params["js"] = array("js/common.js");

    if (!Session::isUserLoggedIn()) {
        header("location: login.php");
    }
    if (!$dbh->isUserAdmin(Session::id()) || !isset($_GET["action"])) {
        header("location: index.php");
    }

    $messages = array(
        0 => "Si è verificato un errore inaspettato",
        1 => "Nome vuoto",
        2 => "Descrizione vuota",
        3 => "Immagine non caricata",
        4 => "Prezzo non valido",
        5 => "Sconto non valido",
        6 => "Disponibilità non valida",
        7 => "Categoria non valida",
        8 => "Gioco non valido",
        9 => "Produttore vuoto"
    );

    // Check if 'itemprocessing.php' has reported an error and show it as an alert.
    if (isset($_GET["error"])) {
        $template_params["alert"] = $errors((int)$_GET["error"] ?? 0);
    }

    // Create an empty item
    $template_params["item"] = array_fill_keys($dbh->getItemColumnNames(), "");
    if (($_GET["action"] === "update" || $_GET["action"] === "delete")) {
        $template_params["item"] = $dbh->getItem($_GET["id"] ?? -1);
        if ($template_params["item"] === false) {
            $template_params["error"] = "Articolo non trovato.";
        }
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

    $template_params["action"] = $_GET["action"];
    $template_params["categories"] = $dbh->getCategories();
    $template_params["brands"] = $dbh->getBrands();

    $template_params["template"] = "itemmanagement-form.php";

    require_once("template/base.php");
?>
