<div class="container">
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

	<form action="./index.php?do=1" method="post" class="top-space">
		<input type="text" class="form-control" placeholder="username" name="username" required>
		<input type="password" class="form-control" placeholder="password" name="password" required>
		<button type="submit" class="btn btn-primary">Login</button>
	</form>
</div>