<?php 
class Session {

    private function __construct() {}

    private static function getLoginSessionFields() {
        return array("userid", "email", "name");
    }

    public static function isUserLoggedIn() {
        foreach (Session::getLoginSessionFields() as $field) {
            if (!isset($_SESSION[$field])) {
                return false;
            }
        }
        return true;
    }

    public static function register($userdata) {
        foreach (Session::getLoginSessionFields() as $field) {
            $_SESSION[$field] = $userdata[$field];
        }
    }

    public static function logout() {
        if (Session::isUserLoggedIn()) {
            foreach (Session::getLoginSessionFields() as $field) {
                unset($_SESSION[$field]);
            }
        }
    }

    public static function id() {
        return Session::isUserLoggedIn() ? $_SESSION["userid"] : -1;
    }

    public static function name() {
        return Session::isUserLoggedIn() ? $_SESSION["name"] : false;
    }
}
?>
