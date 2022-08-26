<?php
    require_once("../bootstrap.php");

    if (!isset($_GET["action"])) {
        header("location: ../index.php");
    }

    switch ($_GET["action"]) {
        case "add":
            if (isset($_GET["id"])) {
                $item = $dbh->getItemDetails($_GET["id"]);
                if ($item != false && $item["itemstock"] > 0) {
                    $result["msg"] = Cart::add($item["itemid"]) ? "Aggiunto" : "Già nel carrello";
                }
            }
            break;
        case "set":
            if (isset($_GET["id"]) && isset($_GET["qty"])) {
                $item = $dbh->getItemDetails($_GET["id"]);
                if ($item != false && $_GET["qty"] <= $item["itemstock"]) {
                    $result["msg"] = Cart::setQuantity($item["itemid"], $_GET["qty"]) ? "Modificato" : "Operazione non riuscita";
                }
            }
            break;
        case "count":
            if (isset($_GET["id"])) {
                $item = $dbh->getItemDetails($_GET["id"]);
                if ($item != false) {
                    $result["msg"] = Cart::count($item["itemid"]);
                }
            }
            break;
        case "whole_cart":
            $item_ids = implode(",", array_keys(Cart::get()));
            $result["cartitems"] = $dbh->getItems(array("itemgroup" => $item_ids));

            prepareItemsForAPI($result["cartitems"]);
            $result["msg"] = "";
            break;
    }
    if (isset($_GET["redirect"])) {
        header("location: ../cart.php");
    }

    $result["countAll"] = Cart::countAll();

    header("Content-Type: application/json");
    echo json_encode($result);
?>
