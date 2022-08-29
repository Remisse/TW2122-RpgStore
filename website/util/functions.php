<?php
    function bigintToCurrencyFormat($number, $separator = ",", $currency = "€") {
        $decimal = substr($number, -2);
        $amount = substr($number, 0, -2).$separator.$decimal;
        return $currency.$amount;
    }

    function stringDecimalToBigint($string) {
        $parts = explode(".", $string);
        $raw = intval(str_replace(".", "", $string));

        // If there's no decimal point, strlen($parts[1]) will return 0.
        return $raw * (10 ** (2 - strlen($parts[1])));
    }

    function stringPercentToFloat($string) {
        return floatval($string) / 100.0;
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

    function isIntegerNumberValid($number) {
        return preg_match("/^[0-9]*$/", $number);
    }

    function isHTMLPriceValid($price) {
        $parts = explode(".", $price);

        if (!(count($parts) == 2 && (strlen($parts[1]) == 1 || strlen($parts[1]) == 2))) {
            return false;
        }
        foreach ($parts as $part) {
            if (!isIntegerNumberValid($part)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Checks whether the given password contains at least one letter and one number.
     */
    function isPasswordValid($password) {
        return preg_match("/^(?:[0-9]+[a-z]|[a-z]+[0-9])[a-z0-9]*$/", $password);
    }

    function uploadImage($path, $image){
        $imageName = basename($image["name"]);
        $fullPath = $path.$imageName;
        
        $maxKB = 500;
        $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
        $result = 0;
        $msg = "";
        //Controllo se immagine è veramente un'immagine
        $imageSize = getimagesize($image["tmp_name"]);
        if($imageSize === false) {
            $msg .= "File caricato non è un'immagine! ";
        }
        //Controllo dimensione dell'immagine < 500KB
        if ($image["size"] > $maxKB * 1024) {
            $msg .= "File caricato pesa troppo! Dimensione massima è $maxKB KB. ";
        }
    
        //Controllo estensione del file
        $imageFileType = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION));
        if(!in_array($imageFileType, $acceptedExtensions)){
            $msg .= "Accettate solo le seguenti estensioni: ".implode(",", $acceptedExtensions);
        }
    
        //Controllo se esiste file con stesso nome ed eventualmente lo rinomino
        if (file_exists($fullPath)) {
            $i = 1;
            do{
                $i++;
                $imageName = pathinfo(basename($image["name"]), PATHINFO_FILENAME)."_$i.".$imageFileType;
            }
            while(file_exists($path.$imageName));
            $fullPath = $path.$imageName;
        }
    
        //Se non ci sono errori, sposto il file dalla posizione temporanea alla cartella di destinazione
        if(strlen($msg)==0){
            if(!move_uploaded_file($image["tmp_name"], $fullPath)){
                $msg.= "Errore nel caricamento dell'immagine.";
            }
            else{
                $result = 1;
                $msg = $imageName;
            }
        }
        return array($result, $msg);
    }

    function flatMap($array, $key) {
        $outVal = array();

        for ($i = 0; $i < count($array); $i++) {
            array_push($outVal, $array[$i][$key]);
        }

        return $outVal;
    }
?>
