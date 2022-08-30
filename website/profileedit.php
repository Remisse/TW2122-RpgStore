<?php
    require_once("bootstrap.php");

    if (!Session::isUserLoggedIn() || !isset($_POST["userid"]) || Session::id() != ($_POST["userid"])) {
        header("location: login.php");
    }

    if (!isset($_POST["email"]) || $_POST["email"] === "" || !isEmailValid($_POST["email"])) {
        $template_params["emailresult"] = "E-mail non valida";
    } else if (isset($_POST["email"]) && $_POST["email"] === Session::email()) {
        // Do nothing
    } else {
        $success = $dbh->updateUserEmail(Session::id(), $_POST["email"]);
        if ($success) {
            Session::register($dbh->getUserDetails(Session::id()));
            $template_params["emailresult"] = "E-mail modificata.";
        } else {
            $template_params["emailresult"] = "Impossibile modificare l'e-mail.";
        }
    }

    if (isset($_POST["newpassword"]) && isPasswordValid($_POST["newpassword"])
        && isset($_POST["oldpassword"]) && password_verify($_POST["oldpassword"], $dbh->checkLogin(Session::email())["password"])
        && isset($_POST["confirmpassword"]) && $_POST["newpassword"] === $_POST["confirmpassword"])
    {
        $template_params["passwordresult"] = $dbh->updateUserPassword(Session::id(), password_hash($_POST["newpassword"], PASSWORD_DEFAULT)) 
                                            ? "Password modificata." 
                                            : "Verifica di aver inserito correttamente le password.";
    } else if ($_POST["newpassword"] === "" && $_POST["oldpassword"] === "" && $_POST["confirmpassword"] === "") {
        // Do nothing
    } else {
        $template_params["passwordresult"] = "Compila correttamente i campi delle password";
    }

    if (isset($_POST["billingaddress"]) && $_POST["billingaddress"] === $dbh->getUserDetails(Session::id())["billingaddress"]) {
        // Do nothing
    } else if (!isset($_POST["billingaddress"]) || $_POST["billingaddress"] === "") {
        $template_params["billingresult"] = "Indirizzo di fatturazione non valido";
    } else {
        $template_params["billingresult"] = $dbh->updateUserBillingAddress(Session::id(), $_POST["billingaddress"])
                                            ? "Indirizzo di fatturazione modificato."
                                            : "Impossibile modificare l'indirizzo di fatturazione.";
    }

    $emailResultAppend = isset($template_params["emailresult"]) ? "&emailresult=".$template_params["emailresult"] : "";
    $passwordResultAppend = isset($template_params["passwordresult"]) ? "&passwordresult=".$template_params["passwordresult"] : "";
    $billingResultAppend = isset($template_params["billingresult"]) ? "&billingresult=".$template_params["billingresult"] : "";

    header("location: login.php?area=profile-content".$emailResultAppend.$passwordResultAppend.$billingResultAppend);
?>
