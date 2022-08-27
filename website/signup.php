<?php
    require_once("bootstrap.php");

    $template_params["js"] = array("js/common.js");
    
    $password_min = 8;
    $success = false;

    if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirmpassword"])) {
        if (strlen($_POST["name"]) == 0 || !isNameValid($_POST["name"])) {
            $template_params["error"] = "Verifica di aver inserito correttamente il tuo nome.";
        } else if (!isEmailValid($_POST["email"])) {
            $template_params["error"] = "Verifica di aver inserito correttamente la tua e-mail.";
        } else if (!$dbh->isEmailAvailable($_POST["email"])) {
            $template_params["error"] = "L'e-mail inserita è già in uso.";
        } else if (strlen($_POST["password"]) < $password_min || !isPasswordValid($_POST["password"])) {
            $template_params["error"] = "La password non rispetta i requisiti di sicurezza.";
        } else if (strcmp($_POST["password"], $_POST["confirmpassword"]) != 0) {
            $template_params["error"] = "Le password non coincidono.";
        } else {
            $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $success = $dbh->insertClient($_POST["email"], $hash, $_POST["name"]);
            
            if (!$success) {
                $template_params["error"] = "Qualcosa è andato storto. Riprova.";
            }
        }
    }
    if ($success) {
        $template_params["title"] = "Registrazione completata";
        $template_params["template"] = "signup-confirmation.php";
    } else {
        $template_params["title"] = "Registrati";
        $template_params["template"] = "signup-form.php";
    }

    require_once("template/base.php");
?>
