<div class="container">

	<?php
		if (count($_POST) == 2) { // alle Argumente gegeben

			$username = $_POST['username'];
			$password = $_POST['password'];

            // get user
            if ($stmt = $con->prepare("SELECT uid, passwort FROM is_user WHERE username=?")) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($uid, $password_db);
                $stmt->fetch();
                $stmt->close();

                // hash input password
                if ($stmt = $con->prepare("SELECT md5(?)")) {
                    $stmt->bind_param("s", $password);
                    $stmt->execute();
                    $stmt->bind_result($password_hash);
                    $stmt->fetch();
                    $stmt->close();

                    // input password != database password
                    if ($password_hash != $password_db) {
                        echo '
                        <div class="top-space">
                            <h3>Fehler!</h3>
                            <p>Du hast ungültige Anmeldedaten eingegeben!</p>
                            <a role="button" class="btn btn-light" href="index.php?do=1">Anmelden</a>
                        </div>
                        ';

                        return;
                    }

                    // right password -> start session with uid
                    $_SESSION["uid"] = $uid;

                    echo '
                    <div class="top-space">
                        <h3>Let\'s go!</h3>
                        <p>Du bist nun angemeldet und kannst Nachrichten verschicken.</p>
                        <a role="button" class="btn btn-light" href="index.php?do=0">Let\'s go</a>
                    </div>
                    ';

                    

                    return;
                }

                return;
            }

            return;
			
			echo '</div>';
		} 
	?>


	<form action="./index.php?do=1" method="post" class="top-space">
		<input type="text" class="form-control" placeholder="Benutzername eingeben" name="username" required>
		<input type="password" class="form-control" placeholder="Passwort eingeben" name="password" required>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>