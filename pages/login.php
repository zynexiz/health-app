<img src="media/logo_small.png" class="mx-auto d-block" alt="logo" height="150">

<div class='container'>
		<div class="p-3 primary text-black text-center">
			<h2>Welcome!</h2>
			<h4>Please enter your member login.</h4>
		</div>
		<br><br>

	<form action="pages/login.php" method="post">
    <?php if (isset($_GET['error'])) { ?>
    <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>
		  
		<label><h5><strong>Username/e-mail</strong></h5></label>
			<div class="input-group">
				<span class="input-group-text bi bi-person-fill"></span>
					<input class="form-control" type="text" name="username" placeholder="Enter your username or e-mail..">
			</div>
			<br>
	
			<label><h5><strong>Password</strong></h5></label>
				<div class="input-group">
					<span class="input-group-text bi bi-key-fill"></span>
						<input class="form-control" type="password" name="password" placeholder="Enter your password..">
				</div>
				<br>
			<button class="btn btn-outline-primary" type="button">Login</button>
</div>	
</form>

<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
		function validate($data){
			$data = htmlspecialchars($data);//prevent browsers from using it as an HTML element. This can be especially useful to prevent code from running when users have access to display input on your homepage.
			return $data;
	}
			$username = validate($_POST['username']);
			$password = validate($_POST['password']);
			
			if(empty($username) || (empty($password))) {//Om användaren inte skrivit in lösen eller användarnamn/email får denne ett fel meddelande.
				header('Location: index.php?error=Username and password is required');
				exit();
	}else{
				$sql = "SELECT * FROM users WHERE username='$username' OR email='$username' AND password='$password'";//
				$result = mysqli_query($conn, $sql);//Resultatet blir förbindelsen till databasen och queryn ovanför.
					if (mysqli_num_rows($result) > 0) {//Om antalet rader av resultatet är större än 1.
						$row = mysqli_fetch_assoc($result);//Itirrerar över raderna i resultat till dessa tar slut.
								$_SESSION['username'] = $row['username'];//Dessa skall byttas till korrekta rows
								$_SESSION['firstname'] = $row['fname'];//Dessa skall byttas till korrekta rows
								$_SESSION['lastname'] = $row['lname'];//Dessa skall byttas till korrekta rows
								$_SESSION['id'] = $row['id'];//Dessa skall byttas till korrekta rows
								header("Location: home.php");//Om allt funkar omdirigeras användaren till dennes hemsida.
								exit();
          }else{
								header('Location: index.php?error=Wrong username or password');//Om användaren skrivit in ett oexisternade lösen eller andvändarnamn/email får denne ett fel meddelande.
								exit();
				}
	}
}
?>
