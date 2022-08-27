<?php
    function bigintToCurrencyFormat($number, $separator = ",", $currency = "€") {
        $decimal = substr($number, -2);
        $amount = substr($number, 0, -2).$separator.$decimal;
        return $currency.$amount;
    }

    function prepareItemsForAPI(&$items) {
        for ($i = 0; $i < count($items); $i++) {
            $items[$i]["pricediscount"] = bigintToCurrencyFormat($items[$i]["pricediscount"]);
            $items[$i]["itemprice"] = bigintToCurrencyFormat($items[$i]["itemprice"]);
    
            $items[$i]["itemimg"] = UPLOAD_DIR.$items[$i]["itemimg"];
        }
    }

    function getIdFromName($name) {
        return preg_replace("/[^a-z]/", '', strtolower($name));
    }

    function toSQLFriendlyIds(array $ids) {
        return implode(",", array_keys($ids));
    }

    function addCartQuantityForEachItem(&$items) {
        for ($i = 0; $i < count($items); $i++) {
            $items[$i]["cartqty"] = Cart::count($items[$i]["itemid"]);
        }
    }

    /**
     * Checks whether the given name contains only letters.
     */
    function isNameValid($name) {
        return preg_match("/^[a-zA-Z-' ]*$/", $name);
    }

    function isEmailValid($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Checks whether the given password contains at least one letter and one number.
     */
    function isPasswordValid($password) {
        return preg_match("/^(?:[0-9]+[a-z]|[a-z]+[0-9])[a-z0-9]*$/", $password);
    }
?>
