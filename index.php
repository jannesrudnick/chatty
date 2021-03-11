<?php
session_start();

include('src/utils/tools.php');
include('src/components/header.php');

if (isset($_GET['do']) && !isset($_SESSION["uid"])) {
    switch ($_GET['do']) {
        case 1:
            include('src/pages/login.php');
            break;
        case 2:
            include('src/pages/register.php');
            break;
        default:
            include('src/pages/home.php');
    } 
} elseif (isset($_GET['do']) && isset($_SESSION["uid"])) {
    switch ($_GET['do']) {
        case 11:
            include('src/pages/messages.php');
            break;
        case 12:
            include('src/pages/profile.php');
            break;
        case 13:
            include('src/pages/chat.php');
            break;
        default:
            include('src/pages/home.php');
    }
} else {
    include('src/pages/home.php');
}

include('src/components/footer.php');