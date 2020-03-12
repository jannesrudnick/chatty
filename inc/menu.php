<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a class="navbar-brand" href="index.php?do=0">Chat</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <?php
                if (!isset($_SESSION["uid"])) {
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?do=1">Anmelden</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?do=2">Registrieren</a>
                    </li>
                    ';
                } else {
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?do=11">Nachrichten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?do=12">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?do=0&a=logout">Abmelden</a>
                    </li>
                    ';
                }
            ?>
            
        </ul>
    </div>

</nav>