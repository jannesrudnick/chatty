<div class="container">

	<?php
		if (
			count($_POST) == 4 &&
			isset($_POST['gender']) &&
			isset($_POST['firstname']) &&
			isset($_POST['lastname']) &&
			isset($_POST['password'])
		) { 

			register(
				$_POST['gender'],
				$_POST['firstname'],
				$_POST['lastname'],
				$_POST['password'],
			);

			return;
		} 
	?>

	<form action="./index.php?do=2" method="post" class='top-space'>
		<div class="form-check-inline">
			<input type="radio" class="form-check-input" id="gender" name="gender" value="male" checked>male
		</div>
		<div class="form-check-inline">
			<input type="radio" class="form-check-input" id="gender" name="gender" value="female">female
		</div>
		<input type="text" class="form-control" placeholder="first name" name="firstname" required>
		<input type="text" class="form-control" placeholder="last name" name="lastname" required>
		<input type="password" class="form-control" placeholder="password" name="password" required>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>