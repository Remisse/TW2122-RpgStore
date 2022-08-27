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
        case "whole_cart":
            $result["cartitems"] = $dbh->getItems(array("itemgroup" => Cart::getRaw()));
            
            for ($i = 0; $i < count($result["cartitems"]); $i++) {
                $result["cartitems"][$i]["cartqty"] = Cart::count($result["cartitems"][$i]["itemid"]);
            }

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
