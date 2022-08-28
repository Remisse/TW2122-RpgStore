<?php
    require_once("../bootstrap.php");

    if (!isset($_GET["action"])) {
        header("location: ../index.php");
    }

    $result["msg"] = "";
    
    switch ($_GET["action"]) {
        case "add":
            $stock = $dbh->getItemStock($_GET["id"] ?? -1);
            if ($stock != null && $stock > 0) {
                $result["msg"] = Cart::add($_GET["id"]) ? "Aggiunto" : "Già nel carrello";
            }
            break;
        case "set":
            $id = $_GET["id"] ?? -1;
            $stock = $dbh->getItemStock($id);
            if ($stock != null && $_GET["qty$id"] <= $stock) {
                $result["msg"] = Cart::setQuantity($id, $_GET["qty$id"]) ? "Modificato" : "Operazione non riuscita";
            }
            break;
        case "whole_cart":
            $result["cartitems"] = $dbh->getItems(array("itemgroup" => Cart::getRaw()));
            addCartQuantityForEachItem($result["cartitems"]);
            prepareItemsForAPI($result["cartitems"]);
            break;
    }
    if (isset($_GET["redirect"])) {
        header("location: ../cart.php");
    }

    $result["countAll"] = Cart::countAll();

    header("Content-Type: application/json");
    echo json_encode($result);
?>
