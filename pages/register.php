 
<div class="Registration-form">
		<h1>Registration</h1>
		<form action="" method="post">
			
		
		<?php
	
		
		if ($_POST) {	
		$fd=dbFetch("SELECT * from ha_users WHERE username='{$_POST['username']}' OR email='{$_POST['email']}'");
		if ($fd == FALSE) {
		if ($_POST["password"] !== $_POST["password2"]) {
			$error=_('Passwords should match eachother');
			
	}
		$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME, DBPORT);
		$conn->set_charset('utf8mb4');
		if ($conn->connect_error) {
		die("DB Connection failed: " . $conn->connect_error);
	}
		$sql = <<<SQL
		INSERT INTO ha_users(username, passwd, email, urole)
		VALUES('{$_POST['username']}','{$_POST['password']}','{$_POST['email']}','2');
SQL;

	/* Die if connection can't be established, else fetch query
	 * and return an array with the data, or empty array if query
	 * returns empty result.
	 */
	if ($conn->query($sql) === TRUE) {
		$id = $conn->insert_id;
		$sql2 = <<<SQL
		INSERT INTO ha_userdata(uid, fname, lname, sex, height, ui_mode, lang, birthdate)
		VALUES('{$id}','{$_POST['fname']}','{$_POST['lname']}','{$_POST['gender']}','0','1','1','{$_POST['birthdate']}')
SQL;
	if ($conn->query($sql2) === TRUE)	{
	}
	} else {
		echo $conn->error;
	}
		} 
		else { 
			$error=_('Username or email already in use');
		}
		}

		if (isset($error)) {
			echo '<div class="alert alert-danger"><strong>'._('Could not create your account').'</strong><br>'.$error."</div>";
		}
		?>
		<div class="">
			<p>First Name:</p>
			<input type="text" name="fname" Placeholder="Enter first name" autocomplete="off" required>
			<p>Last name:</p>
			<input type ="text" name="lname" Placeholder="Enter last name" autocomplete="off" required>
			<p>Username:</p>
			<input type="text" name="username" Placeholder="Enter a username" autocomplete="off" required>
			<p>Email:</p>
			<input type="email" name="email" Placeholder="Enter email" autocomplete="off" required>
			<p>Birthdate</p>
			<input type="date" name="birthdate" autocomplete="off" required>
			<p>Password:</p>
			<input type="password" name="password" Placeholder="Enter password" autocomplete="off" required>
			<p>Confirm password:</p>
			<input type="password" name="password2" Placeholder="Enter password" autocomplete="off" required><br><br>
			<input type="radio" name="gender" value="1"> Male<br>
			<input type="radio" name="gender" value="2"> Female<br>
			<input type="radio" name="gender" value="3"> Other<br><br>
			<button type="submit">Register</button>
			
			
			<p class="center"><br/>
				Already have an account? <a href="./?page=login"><span style="color:blue;">Login here</span></a>
			</p>
			
			
		
		</form>
</div>  

