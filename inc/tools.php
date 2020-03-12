<?php

include('./inc/dbconfig.php');

function special_chars($string) {
    $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´");
    $replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "");
    return str_replace($search, $replace, $string);
}

function generate_username($surname, $name) {
    return strtolower(substr(special_chars($surname), 0, 3) . substr(special_chars($name), 0, 3));
}



?>