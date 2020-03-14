<?php

// actions
if (isset($_GET["a"]) && $_GET["a"] == 'logout') {
    session_destroy();
    echo '<script>location.href = "./index.php?do=0&msg=1"</script>';
}

// messages
// TODO: switch
if (isset($_GET["msg"]) && $_GET["msg"] == '1') {
   echo '<div class="notify">Du hast dich erfolgreich ausgeloggt!</div>';  
}

if (!isset($_SESSION["uid"])) {
    echo '
    <div class="jumbotron text-center">
        <h1>Chat</h1>
        <p>Willkommen bei dieser PHP basierten Chat-Applikation!</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h3>Login</h3>
                <p>Melde dich an um Nachrichten zu versenden.</p>
                <a role="button" class="btn btn-light" href="index.php?do=1">Anmelden</a>
            </div>
            <div class="col-sm-6">
                <h3>Registrierung</h3>
                <p>Registrier dich um mit anderen Benutzern kommunizieren zu können.</p>
                <a role="button" class="btn btn-light" href="index.php?do=2">Registrieren</a>
            </div>
        </div>
    </div>
    ';

    return;
} 

// TODO: add username welcome message
?>

<div class="jumbotron text-center">
    <h1>Guten Tag <?php echo '<code>'.get_username($_SESSION['uid']).'</code>'; ?>!</h1>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <h3>Protokoll</h3>
            <p>Versende und Empfange Nachrichten.</p>
            <a role="button" class="btn btn-light" href="index.php?do=11">Nachrichten</a>
        </div>
        <div class="col-sm-4">
            <h3>Chat</h3>
            <p>Führe ein Direkt Chat mit einem anderen User.</p>
            <a role="button" class="btn btn-light" href="index.php?do=13">Chat's</a>
        </div>
        <div class="col-sm-4">
            <h3>Profil</h3>
            <p>Ändere deine Profileinstellungen.</p>
            <a role="button" class="btn btn-light" href="index.php?do=12">Profil</a>
        </div>
    </div>
</div>


