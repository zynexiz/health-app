<?php 
session_start(); 

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
}else{
	//header('Location: projectindex.php');//Denna skall bytas mot rätt länk.
	exit();
}
?>

<form action="login.php" method="post"> //Denna skall bytas mot rätt länk
        <h2>Welcome to Project health!</h2>
				<h3>Please enter your login</h3>
        <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>Username/e-mail</label>
        <input type="text" name="username" placeholder="Enter your username or e-mail">
				<label>Password</label>
        <input type="password" name="password" placeholder="Enter your password">
        <button type="submit">Login</button>
    </form>
