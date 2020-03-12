<div class="container">

	<?php
		if (count($_POST) == 2) { // alle Argumente gegeben

			$username = $_POST['username'];
			$password = $_POST['password'];

            $_SESSION["uid"] = 1;
            echo '
            <div class="top-space">
                <h3>Let\'s go!</h3>
                <p>Du bist nun angemeldet und kannst Nachrichten verschicken.</p>
                <a role="button" class="btn btn-light" href="index.php?do=0">Let\'s go</a>
            </div>
            ';

            return;

            /*

            // TODO: make prepared?
            $sql = "SELECT uid, password FROM is_user WHERE username='" . $usrlgn . "'";
            $result = $con->query($sql);
            
	        if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $uid = $row['uid'];
                    $password_db = $row['password'];
                }
                
                if (password_verify($password, $password_db)) {
                    $_SESSION["uid"] = $uid;
                    echo '
                    <h3>Let\'s go!</h3>
                    <p>Du bist nun angemeldet und kannst nachrichten verschicken.</p>
                    <a role="button" class="btn btn-light" href="index.php?do=0">Start</a>
                    ';

                    return;
                }	
            } else {
                // TODO: beautify
                echo '
                <h3>Login nicht erfolgreich!</h3>
                ';
            }
            */	
			
			echo '</div>';
		} 
	?>


	<form action="./index.php?do=1" method="post" class="top-space">
		<input type="text" class="form-control" placeholder="Benutzername eingeben" name="username" required>
		<input type="password" class="form-control" placeholder="Passwort eingeben" name="password" required>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>