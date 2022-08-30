<?php
    require_once("bootstrap.php");

    if (!Session::isUserLoggedIn() || !$dbh->isUserAdmin(Session::id()) || !isset($_POST["action"])) {
        header("location: login.php");
    }

    $action = $_POST["action"];
    $error = null; // Error code. Codes are defined in 'itemmanagement.php'.
    if ($action === "insert" || $action === "update") {
        if (!isset($_POST["itemname"])) {
            $error = 1;
        } else if (!isset($_POST["itemdescription"])) {
            $error = 2;
        } else if ($action === "insert" && !isset($_FILES["itemimg"])) {
            $error = 3;
        } else if (!(isset($_POST["itemprice"]) && isHTMLPriceValid(strval($_POST["itemprice"])) && $_POST["itemprice"] >= 0)) {
            $error = 4;
        } else if (!(isset($_POST["itemdiscount"]) && isIntegerNumberValid(strval($_POST["itemdiscount"])) && $_POST["itemdiscount"] >= 0 && $_POST["itemdiscount"] <= 100)) {
            $error = 5;
        } else if (!(isset($_POST["itemstock"]) && isIntegerNumberValid(strval($_POST["itemstock"])) && $_POST["itemstock"] >= 0)) {
            $error = 6;
        } else if (!(isset($_POST["itemcategory"]) && in_array($_POST["itemcategory"], flatMap($dbh->getCategories(), "categoryid")))) {
            $error = 7;
        } else if (isset($_POST["itembrand"]) && !in_array($_POST["itembrand"], flatMap($dbh->getBrands(), "brandid"))) {
            $error = 8;
        } else if (!isset($_POST["itemcreator"])) {
            $error = 9;
        }

        if (isset($error)) {
            header("location: itemmanagement.php?action=".$action
                .(isset($_POST["itemid"]) ? "&id=".$_POST["itemid"] : "")
                ."&error=".$error);
        }

        $parameters = array();
        $parameters["itemname"] = htmlspecialchars($_POST["itemname"]);
        $parameters["itemdescription"] = htmlspecialchars($_POST["itemdescription"]);

        $parameters["itemprice"] = stringDecimalToBigint(strval($_POST["itemprice"]));
        $parameters["itemdiscount"] = stringPercentToFloat(strval($_POST["itemdiscount"]));

        $parameters["itemstock"] = $_POST["itemstock"];
        $parameters["itemcategory"] = $_POST["itemcategory"];
        $parameters["itembrand"] = isset($_POST["itembrand"]) ? htmlspecialchars($_POST["itembrand"]) : null;
        $parameters["itemcreator"] = $_POST["itemcreator"];
        $parameters["itempublisher"] = isset($_POST["itempublisher"]) ? htmlspecialchars($_POST["itempublisher"]) : null;

        if (isset($_FILES["itemimg"]) && isset($_POST["itemimg"]) && $_POST["itemimg"] !== "") {
            list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["itemimg"]);
            if ($result == 0) {
                header("location: login.php?area=adminarea-content&formmsg=".$msg);
            } 
            $parameters["itemimg"] = $msg;
        } else {
            // If the image hasn't been uploaded and we've got up to this point, it means the user is editing the item and is fine with the current image.
            $parameters["itemimg"] = $dbh->getItemImage($_POST["itemid"]);
        }

        if ($action === "insert") {
            $msg = $dbh->insertItem($parameters) ? "Inserimento avvenuto correttamente" : "Errore durante l'inserimento dell'articolo.";
        } else {
            $parameters["itemid"] = $_POST["itemid"] ?? -1;
            $msg = $dbh->updateItem($parameters) ? "Articolo modificato con successo." : "Errore durante la modifica dell'articolo.";
        }
    } else if ($action === "delete") {
        $itemid = $_POST["itemid"] ?? -1;
        $msg = $dbh->markItemAsDeleted($itemid) ? "Articolo eliminato con successo." : "Errore durante l'eliminazione dell'articolo";
    } else if (($_GET["action"] ?? "") === "restore") {
        // TODO Maybe create an ad-hoc page instead of hijacking this one
        $itemid = $_GET["itemid"] ?? -1;
        $msg = $dbh->restoreDeletedItem($_GET["id"] ?? -1) ? "Articolo recuperato con successo." : "Impossibile recuperare l'articolo";
        header("location: login.php?area=itembin-content&formmsg=".$msg);
    }
    header("location: login.php?area=adminarea-content&formmsg=".$msg);
?>
