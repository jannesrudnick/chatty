<?php

// action -> logout
if (isset($_GET["a"]) && $_GET["a"] == 'logout') {
    session_destroy();
    echo '<script>location.href = "./index.php"</script>';
}

// display -> msg
if (isset($_GET["msg"]) && $_GET["msg"] == '1') {
   echo '<div class="notify">you logged out</div>';
}

// view -> login / register
if (!isset($_SESSION["uid"])) {
    echo '
    <div class="jumbotron text-center">
        <h1>✉️ Chatty</h1>
        <p>Welcome to this php-based Chat-Application 🚀</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h3>👤 Login</h3>
                <p>Login to start chatting.</p>
                <a role="button" class="btn btn-light" href="index.php?do=1">Login</a>
            </div>
            <div class="col-sm-6">
                <h3>👤 Register</h3>
                <p>Register to Chat with users.</p>
                <a role="button" class="btn btn-light" href="index.php?do=2">Register</a>
            </div>
        </div>
    </div>
    ';

    return;
}

// view -> logged in
?>

<div class="jumbotron text-center">
    <h1>Welcome <?php echo '<code>'.get_username($_SESSION['uid']).'</code>'; ?>!</h1>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <h3>🗒 Protocol</h3>
            <p>Send und receive messages.</p>
            <a role="button" class="btn btn-light" href="index.php?do=11">Messages</a>
        </div>
        <div class="col-sm-4">
            <h3>🖋 Chat</h3>
            <p>Start a direct chat with a user.</p>
            <a role="button" class="btn btn-light" href="index.php?do=13">Chat's</a>
        </div>
        <div class="col-sm-4">
            <h3>👤 Profile</h3>
            <p>Customize your profile.</p>
            <a role="button" class="btn btn-light" href="index.php?do=12">Profile</a>
        </div>
    </div>
</div>


