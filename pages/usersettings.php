<?php
	# Fetch the available languages and UI modes
	$uimode = dbFetch("SELECT * FROM " . DBPREFIX . "uimode;");
	$lang = dbFetch("SELECT * FROM " . DBPREFIX . "lang;");

	if ($_POST) {
		# Array for what data to expect in $_POST and how to verify its data correctly
		$dataCheck = array(
			'username' => array('type' => 'username', 'db' => 'users', 'err' => false),
			'email' => array('type' => 'email', 'db' => 'users', 'err' => false),
			'fname' => array('type' => 'name', 'db' => 'userdata', 'err' => false),
			'lname' => array('type' => 'name', 'db' => 'userdata', 'err' => false),
			'birthdate' => array('type' => 'date', 'db' => 'userdata', 'err' => false),
			'height' => array('type' => 'int', 'db' => 'userdata', 'err' => false),
			'lang' => array('type' => 'int', 'db' => 'userdata', 'err' => false),
			'ui_mode' => array('type' => 'int', 'db' => 'userdata', 'err' => false),
			'sex' => array('type' => 'int', 'db' => 'userdata', 'err' => false),
		);
		if ($_POST['passwd']) {
			$dataCheck['passwd'] = array('type' => 'password', 'db' => 'users', 'err' => false);
		}

		# Loop thru $_POST and sanitize user input. If ok add the data to query.
		$hasError = false;
		$sql = 'UPDATE '.DBPREFIX.'users, '.DBPREFIX.'userdata SET ';
		foreach ($dataCheck as $key => $val) {
			$data = verifyData($_POST[$key], $val['type'], false);
			if (!$data) {
				$dataCheck[$key]['err'] = true;
				$hasError = true;
			} else {
				if (($key == "passwd") and ($data != $_POST['conf_password'])) {
					$dataCheck[$key]['err'] = true;
					$hasError = true;
				}
				$sql .= DBPREFIX.$val['db'].'.'.$key.' = "'.$data.'", ';
			}
		}
		$sql = substr($sql, 0, strlen($sql)-2) . " WHERE ".DBPREFIX."users.uid = '". $_SESSION['id']."' AND ".DBPREFIX."userdata.uid = '". $_SESSION['id']."'";

		# Update session variables to reflect the changes
		if ((dbQuery($sql) === true) and (!$hasError)) {
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['firstname'] = $_POST['fname'];
			$_SESSION['lastname'] = $_POST['lname'];
			$_SESSION['gender'] = $_POST['gender'];
			$_SESSION['height'] = $_POST['height'];
			$_SESSION['theme'] = $uimode[$_POST['ui_mode']-1]['css'];
			$_SESSION['lang'] = $lang[$_POST['lang']-1]['code'];
			$_SESSION['birthdate'] = $_POST['birthdate'];
			header("Location: ?page=usersettings&u=ok");
		} else {
			echo '<div class="alert alert-danger"><strong>'._('An error occurred during update').'</strong></div>';
		}
	}

	echo isset($_GET['u']) ? '<div class="alert alert-success"><strong>'._('User information updated').'</strong></div>' : '';
?>

<form id="userForm" action="?page=usersettings" method="post">
	<h2><?php echo _('Account information') ?></h2>
	<p><i>
		<?php
		echo _("Leave password blank if you don't want to change it");
		?>
	</i></p>
	<div class="container float-start">
		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Username') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill"></span>
					<input class="form-control" type="text" name="username" placeholder="<?php echo _('Enter a username') ?>" value="<?php echo $_SESSION['username']?>" required>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('E-mail') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-envelope-fill"></span>
					<input class="form-control" type="text" name="email" placeholder="<?php echo _('Enter your e-mail') ?>" value="<?php echo $_SESSION['email']?>" required>
				</div>
			</div>
		</div>

		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Password') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-key-fill"></span>
					<input class="form-control" type="password" name="passwd" placeholder="<?php echo _('Enter new password') ?>">
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Confirm password') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-key-fill"></span>
					<input class="form-control" type="password" name="conf_password" placeholder="<?php echo _('Confirm new password') ?>">
				</div>
			</div>
		</div>
		<div class="line"></div>

		<h2><?php echo _('Personal information') ?></h2>
		<p><i>
			<?php
			echo _('Enter your personal information below.');
			?>
		</i></p>
		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('First name') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill"></span>
					<input class="form-control" type="text" name="fname" placeholder="<?php echo _('Your first name') ?>" value="<?php echo $_SESSION['firstname']?>" required>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Last name') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill"></span>
					<input class="form-control" type="text" name="lname" placeholder="<?php echo _('Your last name') ?>" value="<?php echo $_SESSION['lastname']?>" required>
				</div>
			</div>
		</div>

		<div class="row justify-content-start">
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Date of birth') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-calendar-event-fill"></span>
					<input class="form-control" type="date" name="birthdate" value="<?php echo $_SESSION['birthdate']?>" required>
				</div>
			</div>
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Length') ?></label>
				<div class="input-group" style="">
					<span class="input-group-text bi bi-rulers"></span>
					<input class="form-control" type="number" name="height" placeholder="<?php echo _('Length (cm)') ?>" min="50" max="230" value="<?php echo $_SESSION['height']?>" required>
					<span class="input-group-text">cm</span>
				</div>
			</div>
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Gender') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-gender-ambiguous"></span>
					<select class="form-select" name="sex">
						<?php
							echo '<option value="1"'.(($_SESSION['gender'] == 1) ? ' selected' : '').'>'._('Male').'</option>';
							echo '<option value="2"'.(($_SESSION['gender'] == 2) ? ' selected' : '').'>'._('Female').'</option>';
							echo '<option value="3"'.(($_SESSION['gender'] == 3) ? ' selected' : '').'>'._('Other').'</option>';
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="line"></div>

		<h2><?php
			echo _('Interface options')
			?>
		</h2>
		<p><i>
			<?php
			echo _('Change behaviour, language and colour style.');
			?>
		</i></p>
			<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Application language') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-translate"></span>
					<select class="form-select" name="lang">
					<?php
						foreach ($lang as $l) {
							echo '<option value="'.$l['langid'].'" '.(($_SESSION['lang'] == $l['code']) ? ' selected' : '').'>'._($l['lang']).'</option>';
						}
					?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Interface colour') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-palette-fill"></span>
					<select class="form-select" name="ui_mode">
					<?php
						foreach ($uimode as $ui) {
							echo '<option value="'.$ui['id'].'" '.(($_SESSION['theme'] == $ui['css']) ? ' selected' : '').'>'._($ui['uiname']).'</option>';
						}
					?>
					</select>
				</div>
			</div>
		</div>
		<div class="line"></div>
		<button class="btn btn-primary" type="submit"><?php echo _('Save changes') ?></button>
	</div>
</form>
