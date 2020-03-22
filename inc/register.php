<div class="container">

	<!-- VIEW1 -->
	<?php
		if (
			count($_POST) == 4 &&
			isset($_POST['salutation']) &&
			isset($_POST['name']) &&
			isset($_POST['surname']) &&
			isset($_POST['password'])
		) { 

			register(
				$_POST['salutation'],
				$_POST['name'],
				$_POST['surname'],
				$_POST['password'],
			);

			return;
		} 
	?>

    <!-- VIEW2 -->
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