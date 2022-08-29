<?php
    require_once("bootstrap.php");

    if (!Session::isUserLoggedIn() || !isset($_POST["action"])) {
        header("location: login.php");
    }

    $action = $_POST["action"];
    if ($action === "insert" || $action === "update") {
        if (!isset($_POST["itemname"])) {
            $template_params["msg"] = "Nome vuoto";
        } else if (!isset($_POST["itemdescription"])) {
            $template_params["msg"] = "Descrizione vuota";
        } else if ($action === "insert" && !isset($_FILES["itemimg"])) {
            $template_params["msg"] = "Immagine non caricata";
        } else if (!(isset($_POST["itemprice"]) && isHTMLPriceValid(strval($_POST["itemprice"])) && $_POST["itemprice"] >= 0)) {
            $template_params["msg"] = "Prezzo non valido (".$_POST["itemprice"].")";
        } else if (!(isset($_POST["itemdiscount"]) && isIntegerNumberValid(strval($_POST["itemdiscount"])) && $_POST["itemdiscount"] >= 0 && $_POST["itemdiscount"] <= 100)) {
            $template_params["msg"] = "Sconto non valido";
        } else if (!(isset($_POST["itemstock"]) && isIntegerNumberValid(strval($_POST["itemstock"])) && $_POST["itemstock"] >= 0)) {
            $template_params["msg"] = "Disponibilità non valida";
        } else if (!(isset($_POST["itemcategory"]) && in_array($_POST["itemcategory"], flatMap($dbh->getCategories(), "categoryid")))) {
            $template_params["msg"] = "Categoria non valida (".$_POST["itemcategory"].")";
        } else if (isset($_POST["itembrand"]) && !in_array($_POST["itembrand"], flatMap($dbh->getBrands(), "brandid"))) {
            $template_params["msg"] = "Gioco non valido";
        } else if (!isset($_POST["itemcreator"])) {
            $template_params["msg"] = "Produttore vuoto";
        }

        if (isset($template_params["msg"])) {
            header("location: itemmanagement.php?action=".$action
                .(isset($_POST["itemid"]) ? "&id=".$_POST["itemid"] : "")
                ."&msg=".htmlentities($template_params["msg"]));
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
                header("location: adminarea.php?formmsg=".$msg);
            } 
            $parameters["itemimg"] = $msg;
        } else {
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
        $msg = $dbh->deleteItem($itemid) ? "Articolo eliminato con successo." : "Errore durante l'eliminazione dell'articolo";
    }
    header("location: adminarea.php?formmsg=".$msg);
?>
