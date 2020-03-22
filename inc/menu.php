<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a class="navbar-brand" href="index.php?do=0">Chat</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        
            <?php
                if (!isset($_SESSION["uid"])) {
                    echo '
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?do=1">Anmelden</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?do=2">Registrieren</a>
                        </li>
                    </ul>
                    ';
                } else {
                    $username = get_username($_SESSION['uid']);
                    echo "
                    <ul class='navbar-nav mr-auto'>
                        <li class='nav-item'>
                            <a class='nav-link' href='index.php?do=11'>Nachrichten</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' href='index.php?do=13'>Chat's</a>
                        </li>
                    </ul>
                    <ul class='navbar-nav'>
                        <li class='nav-item'>
                            <a class='nav-link' href='index.php?do=12'>$username</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' href='index.php?do=0&a=logout'>Abmelden</a>
                        </li>
                    </ul>
                    ";
                }
            ?>
        </ul>
    </div>

</nav>