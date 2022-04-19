<?php
	if ($_POST) {

	}
?>

<form id="userForm" action="?page=usersettings" method="post">
	<h2><?php echo _('Account information') ?></h2>
	<p><i>
		<?php
		echo _('Leave password blank if you don\'t want to change it. You need to confirm changes here with you current password.');
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
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Password') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-key-fill"></span>
					<input class="form-control" type="password" name="new_password" placeholder="<?php echo _('Enter new password') ?>">
				</div>
			</div>
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Confirm password') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-key-fill"></span>
					<input class="form-control" type="password" name="conf_password" placeholder="<?php echo _('Confirm new password') ?>">
				</div>
			</div>
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Current password') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-key-fill"></span>
					<input class="form-control" type="password" name="password" placeholder="<?php echo _('Current password') ?>">
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
					<input class="form-control" type="number" name="length" placeholder="<?php echo _('Length (cm)') ?>" min="50" max="230" value="<?php echo $_SESSION['height']?>" required>
					<span class="input-group-text">cm</span>
				</div>
			</div>
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Gender') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-gender-ambiguous"></span>
					<select class="form-select">
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
			$uimode = dbFetch("SELECT * FROM " . $CONFIG['dbtableprefix'] . "uimode;");
			$lang = dbFetch("SELECT * FROM " . $CONFIG['dbtableprefix'] . "lang;");
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
							echo '<option value="'.$l['code'].'" '.(($_SESSION['lang'] == $l['code']) ? ' selected' : '').'>'._($l['lang']).'</option>';
						}
					?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Interface colour') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-palette-fill"></span>
					<select class="form-select" name="ui">
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
		<button class="btn btn-outline-primary" type="submit"><?php echo _('Save changes') ?></button>
	</div>
</form>
