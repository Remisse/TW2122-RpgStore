<?php
class Cart {
    private function __construct() {}

    public static function empty() {
        $_SESSION["cart"] = array();
    }

    private static function init() {
        if (!isset($_SESSION["cart"])) {
            Cart::empty();
        }
    }

    public static function add(int $itemid) {
        Cart::init();

        if (!isset($_SESSION["cart"][$itemid])) {
            $_SESSION["cart"][$itemid] = 1;
            return true;
        }
        return false;
    }
    
    public static function setQuantity(int $itemid, int $qty) {
        Cart::init();

        if ($qty > 0) {
            $_SESSION["cart"][$itemid] = $qty;
        }
        else {
            unset($_SESSION["cart"][$itemid]);
        }
    }

    public static function count(int $itemid) {
        Cart::init();

        return isset($_SESSION["cart"][$itemid]) ? $_SESSION["cart"][$itemid] : 0;
    }

    public static function countAll() {
        Cart::init();

        return array_sum($_SESSION["cart"]);
    }

    public static function get() {
        Cart::init();

        return $_SESSION["cart"];
    }
}
?>
