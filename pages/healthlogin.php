
<?php 
session_start(); 
include 'Här skall databasen vi använder skrivas';

if (isset($_POST['username']) && isset($_POST['password'])) {
	//
	function validate($data){
       	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
	}

	$username = validate($_POST['username']);
	$password = validate($_POST['password']);

	//Om antingen användarnamnet eller lösenordet inte anges innan inloggnigen får användaren ett meddelande att dessa måste fyllas i.
	if (empty($username)) {
		header('Location: projectindex.php?error=Username is required'); //Denna skall bytas ut mot korrekt länk
	    exit();
	}else if(empty($password)){
        header('Location: projectindex.php?error=Password is required'); //Denna skall bytas ut mot korrekt länk
	    exit();
	}else{
		$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'"; //Hämtar all info från kring användaren databasen.

		$result = mysqli_query($conn, $sql); //

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['username'] === $username && $row['password'] === $password) {
            	$_SESSION['username'] = $row['username'];
            	$_SESSION['firstname'] = $row['fname'];
		$_SESSION['lastname'] = $row['lname'];
            	$_SESSION['id'] = $row['id'];
            	header("Location: projecthome.php"); //Denna skall byttas ut mot korrekt länk.
		        exit();
            }else{
		header('Location: projectindex.php?error=Incorrect username or password'); //Denna skall bytas ut mot korrekt länk. //Om inte användaren anger rätt information får denne ett meddelande.
		        exit();
			}
		}else{
			header('Location: projectindex.php?error=Incorrect username or password'); //Denna skall bytas ut mot korrekt länk. //Om inte användaren anger rätt information får denne ett meddelande.
	        exit();
		}
	}
	
}else{
	header('Location: projectindex.php'); //Denna skall bytas ut mot korrekt länk
	exit();
}

<form action="projectlogin.php" method="post"> //Denna skall bytas mot korrekt länk
        <h2>Welcome to Project health!</h2>
	<h3>Please enter your login</h3>
        <?php if (isset($_GET['error'])) { ?> //Fångar upp URL:en och skriver ett meddelande till användaren.
                <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>Username</label>
        <input type="text" name="username" placeholder="Username..">
	<label>Password</label>
        <input type="password" name="password" placeholder="Password..">
        <button type="submit">Login</button>
    </form>
