<div class="container">
    <!-- VIEW1 -->
	<?php
        if (
            count($_POST) == 2 &&
            isset($_POST['username']) &&
            isset($_POST['password']) 
        ) { 

            login(
                $_POST['username'],
                $_POST['password'],
            );

            return;
        }
	?>

    <!-- VIEW2 -->
	<form action="./index.php?do=1" method="post" class="top-space">
		<input type="text" class="form-control" placeholder="Benutzername eingeben" name="username" required>
		<input type="password" class="form-control" placeholder="Passwort eingeben" name="password" required>
		<button type="submit" class="btn btn-primary">Absenden</button>
	</form>
</div>