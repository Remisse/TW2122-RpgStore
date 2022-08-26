<?php
    function bigintToCurrencyFormat($number, $separator = ",", $currency = "€") {
        $decimal = substr($number, -2);
        $amount = substr($number, 0, -2).$separator.$decimal;
        return $currency.$amount;
    }

    function prepareItemsForAPI(&$items) {
        for ($i = 0; $i < count($items); $i++) {
            $discount = $items[$i]["itemdiscount"];
            if ($discount > 0.0) {
                $items[$i]["pricediscount"] = bigintToCurrencyFormat(intval($items[$i]["itemprice"] - $items[$i]["itemprice"] * ($discount * 100.0) / 100));
            }
            $items[$i]["itemprice"] = bigintToCurrencyFormat($items[$i]["itemprice"]);
    
            $items[$i]["itemimg"] = UPLOAD_DIR.$items[$i]["itemimg"];
        }
    }
?>
