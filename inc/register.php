<div class="container">

	<?php
		if (count($_POST) == 4) { // alle Argumente gegeben

			$salutation = $_POST['salutation'];
			$name = $_POST['name'];
			$surname = $_POST['surname'];
			$username = generate_username($surname, $name);
			$unhashed_password = $_POST['password'];

			// prepared statement
			$stmt = $con->prepare("INSERT INTO is_user (anrede, nachname, vorname, username, passwort) VALUES (?, ?, ?, ?, md5(?))");
			$stmt->bind_param("sssss", $salutation, $name, $surname, $username, $unhashed_password);

			if ($stmt->execute()) {
				echo '
				<div class="top-space">
					<h3>Glückwunsch</h3>
					<p>Du kannst dich nun anmelden um Nachrichten zu versenden.</p>
					<a role="button" class="btn btn-light" href="index.php?do=1">Anmelden</a>
				</div>
				';
			} else {
				echo "Error: " . $sql . "<br>" . $con->error;
			}
			
			echo '</div>';

			return; // return code to hide form
		} 
	?>


	<form action="./index.php?do=2" method="post" class='top-space'>
		<div class="form-check-inline">
			<input type="radio" class="form-check-input" id="salutation" name="salutation" value="Herr" checked>Herr
		</div>
		<div class="form-check-inline">
			<input type="radio" class="form-check-input" id="salutation" name="salutation" value="Frau">Frau
		</div>
		<input type="text" class="form-control" placeholder="Vornamen eingeben" name="surname" required>
		<input type="text" class="form-control" placeholder="Nachnamen eingeben" name="name" required>
		<input type="password" class="form-control" placeholder="Passwort eingeben" name="password" required>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>