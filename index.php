<?php
session_start();

include('inc/tools.php');

// load site
include('inc/header.php');

if (isset($_GET['do']) && !isset($_SESSION["uid"])) {
    switch ($_GET['do']) {
        case 1:
            include('inc/login.php');
            break;
        case 2:
            include('inc/register.php');
            break;
        default:
            include('inc/home.php');
    } 
} elseif (isset($_GET['do']) && isset($_SESSION["uid"])) {
    switch ($_GET['do']) {
        case 11:
            include('inc/messages.php');
            break;
        case 12:
            include('inc/profile.php');
            break;
        case 13:
            include('inc/chat.php');
            break;
        default:
            include('inc/home.php');
    }
} else {
    include('inc/home.php');
}


include('inc/footer.php');

// detail view of messages with readed state
// show unread in message appearence
// person - direct - chat only view
// register check if alredy exists
?>