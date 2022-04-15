<?php

if (!empty($_POST)) {
	# Verify username/e-mail and password before quering database
	$username = (strpos($_POST['username'], "@") !== false) ? verifyData( $_POST['username'], "email", $abort_on_error = false) : verifyData( $_POST['username'], "name", $abort_on_error = false);
	$password = verifyData($_POST['password'], "password", $abort_on_error = false);

	# Om verifikationen inte godkänns får användaren ett felmeddelande
	if(empty($username) || (empty($password))) {
		$errLogin = _('Wrong username or password entered');
	} else {
		$sql = "SELECT * FROM users WHERE username='$username' OR email='$username' AND password='$password'";
		$result = mysqli_query($dbConn, $sql); # Resultatet blir förbindelsen till databasen och queryn ovanför.

		if (mysqli_num_rows($result) > 0) { #Om antalet rader av resultatet är större än 1.
			$row = mysqli_fetch_assoc($result); # Itirrerar över raderna i resultat till dessa tar slut.
				$_SESSION['username'] = $row['username']; # Dessa skall byttas till korrekta rows
				$_SESSION['firstname'] = $row['fname']; # Dessa skall byttas till korrekta rows
				$_SESSION['lastname'] = $row['lname']; # Dessa skall byttas till korrekta rows
				$_SESSION['id'] = $row['id']; # Dessa skall byttas till korrekta rows
				header("Location: ?page=home"); # Om allt funkar omdirigeras användaren till dennes hemsida.
		} else {
			$errLogin = _('Wrong username or password entered');
		}
	}
}
?>
<img src="media/logo_small.png" class="mx-auto d-block" alt="logo" height="150">

<div class='container'>
	<div class="p-3 primary text-black text-center">
		<h2>Welcome!</h2>
		<h4>Please enter your member login.</h4>
	</div>
	<br><br>
	<?php
		if (isset($errLogin)) {
			echo '<div class="alert alert-danger"><strong>'._('Could not login to your account').'</strong><br>'.$errLogin."</div>";
		}
	?>
	<form id="loginForm" action="?page=login" method="post">
		<label><h5><strong>Username/e-mail</strong></h5></label>
			<div class="input-group">
				<span class="input-group-text bi bi-person-fill"></span>
					<input class="form-control" type="text" name="username" placeholder="Enter your username or e-mail.." required>
			</div>
			<br>

			<label><h5><strong>Password</strong></h5></label>
				<div class="input-group">
					<span class="input-group-text bi bi-key-fill"></span>
						<input class="form-control" type="password" name="password" placeholder="Enter your password.." required>
				</div>
				<br>
			<button class="btn btn-outline-primary" type="submit">Login</button>
		</form>
</div>

