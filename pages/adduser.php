<?php
	# Array for what data to expect in $_POST and how to verify its data correctly
	$dataCheck = array(
		'username' => array('type' => 'username', 'err' => false),
		'email' => array('type' => 'email', 'err' => false),
		'fname' => array('type' => 'name', 'err' => false),
		'lname' => array('type' => 'name', 'err' => false),
		'birthdate' => array('type' => 'date', 'err' => false),
		'gender' => array('type' => 'int', 'err' => false),
		'password' => array('type' => 'password', 'err' => false),
		'password2' => array('type' => 'password', 'err' => false)
	);

	# Iterate thru the array, verify the data and build the SQL query
	if ($_POST) {
		if ($_POST["password"] !== $_POST["password2"]) {
			$error=_('Passwords does not match.');
		} else {
			foreach ($dataCheck as $key => $val) {
				$data = verifyData($_POST[$key], $val['type'], false);
				if (!$data) {
					$dataCheck[$key]['err'] = true;
					$hasError = true;
				} else {
					$error=_('Some information is not correct');
				}
			}
			if (!$hasError) {
				$fd=dbFetch("SELECT * from ha_users WHERE username='{$_POST['username']}' OR email='{$_POST['email']}'");
				if ($fd == FALSE) {
					$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME, DBPORT);
					$conn->set_charset('utf8mb4');
					if ($conn->connect_error) {
						die("DB Connection failed: " . $conn->connect_error);
					}
					$sql = <<<SQL
					INSERT INTO ha_users(username, passwd, email, urole)
					VALUES('{$_POST['username']}','{$_POST['password']}','{$_POST['email']}','2');
SQL;

					if ($conn->query($sql) === TRUE) {
						$id = $conn->insert_id;
						$sql2 = <<<SQL
						INSERT INTO ha_userdata(uid, fname, lname, sex, height, ui_mode, lang, birthdate)
						VALUES('{$id}','{$_POST['fname']}','{$_POST['lname']}','{$_POST['gender']}','0','1','1','{$_POST['birthdate']}')
SQL;
						if ($conn->query($sql2) === TRUE)	{
							header("Location: ?page=adduser&reg=1");
						}
					} else {
						$error = _("Something went wrong, can't create account.");
					}
				} else {
					$error = _('Username or email already in use');
				}
			}
		}
	}

	if (isset($error)) {
		echo '<div class="alert alert-danger"><strong>'._('Could not create your account').'</strong><br>'.$error."</div>";
	}
?>

<h2><?php echo _('Add new user');?></h2>
<?php
		if (isset($_GET['reg'])) {
			echo '<div class="alert alert-success">'._('Account has been created')."</div>";
		}
	?>
<form id="regForm" action="?page=adduser" method="post">
	<div class="container float-start">
		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('First name') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill <?php echo ($dataCheck['fname']['err']) ? 'input-error' : '' ?>"></span>
					<input class="form-control <?php echo ($dataCheck['fname']['err']) ? 'input-error' : '' ?>" type="text" name="fname" required>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Last name') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill <?php echo ($dataCheck['lname']['err']) ? 'input-error' : '' ?>"></span>
					<input class="form-control <?php echo ($dataCheck['lname']['err']) ? 'input-error' : '' ?>" type="text" name="lname" required>
				</div>
			</div>
		</div>

		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Username') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill <?php echo ($dataCheck['username']['err']) ? 'input-error' : '' ?>"></span>
					<input class="form-control <?php echo ($dataCheck['username']['err']) ? 'input-error' : '' ?>" type="text" name="username" required>
				</div>
			</div>
		</div>

		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('E-mail') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill <?php echo ($dataCheck['email']['err']) ? 'input-error' : '' ?>"></span>
					<input class="form-control <?php echo ($dataCheck['email']['err']) ? 'input-error' : '' ?>" type="text" name="email" required>
				</div>
			</div>
		</div>

		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Password') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill <?php echo ($dataCheck['password']['err']) ? 'input-error' : '' ?>"></span>
					<input class="form-control <?php echo ($dataCheck['password']['err']) ? 'input-error' : '' ?>" type="password" name="password" required>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Confirm password') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill <?php echo ($dataCheck['password2']['err']) ? 'input-error' : '' ?>"></span>
					<input class="form-control <?php echo ($dataCheck['password2']['err']) ? 'input-error' : '' ?>" type="password" name="password2" required>
				</div>
			</div>
		</div>

		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Date of birth') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill <?php echo ($dataCheck['birthdate']['err']) ? 'input-error' : '' ?>"></span>
					<input class="form-control <?php echo ($dataCheck['birthdate']['err']) ? 'input-error' : '' ?>" type="date" name="birthdate" required>
				</div>
			</div>
		</div>

		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Gender') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill <?php echo ($dataCheck['gender']['err']) ? 'input-error' : '' ?>"></span>
					<select class="form-select <?php echo ($dataCheck['gender']['err']) ? 'input-error' : '' ?>" name="gender">
						<option value="1"><?php echo _('Male')?></option>
						<option value="2"><?php echo _('Female')?></option>
						<option value="3"><?php echo _('Other')?></option>
					</select>
				</div>
			</div>
		</div>
		<div class="line"></div>
		<button class="btn btn-primary" type="submit"><?php echo _('Register') ?></button>
	</div>
