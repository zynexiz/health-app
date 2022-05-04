<?php
$gt =  dbFetch("SELECT * FROM ha_users;");
                  
	
	if ($_POST) { 
	
	
		if (isset($_POST['user'])) {
            $prefix = DBPREFIX;
            $sql = <<<SQL
            SELECT ha_users.uid,username,email,urole,fname,lname,sex,height,birthdate FROM {$prefix}users
            LEFT JOIN ha_userdata ON ha_users.uid=ha_userdata.uid
            WHERE (ha_users.username='{$_POST['user']}')
SQL;
            $queryUser = dbFetch($sql);
						if (!$queryUser) {
							echo '<div class="alert alert-danger"><strong>'._('User does not exist').'</strong></div>';
						} else {
							$hasUserInfo = true;
						}
						
		
				} elseif (isset($_POST['delete'])) {
					$prefix = DBPREFIX;
					$sql = <<<SQL
					DELETE FROM ha_users WHERE uid='{$_POST['uid']}'
SQL;
					$deleteUser = dbQuery($sql);
					if (!$deleteUser) {
					echo '<div class="alert alert-danger"><strong>'._('User could not be deleted!').'</strong></div>';
					} else {
						$gt =  dbFetch("SELECT * FROM ha_users;");
						echo '<div class="alert alert-success"><strong>'._('User has been deleted!').'</strong></div>';
					}
						
					
					
		
		//Array of user information
		} else {
			$dataCheck = array(
				'username' => array('type' => 'username', 'db' => 'users', 'err' => false),
				'email' => array('type' => 'email', 'db' => 'users', 'err' => false),
				'fname' => array('type' => 'name', 'db' => 'userdata', 'err' => false),
				'lname' => array('type' => 'name', 'db' => 'userdata', 'err' => false),
				'birthdate' => array('type' => 'date', 'db' => 'userdata', 'err' => false),
				'height' => array('type' => 'int', 'db' => 'userdata', 'err' => false),
				'sex' => array('type' => 'int', 'db' => 'userdata', 'err' => false),
			);
			if ($_POST['passwd']) {
				$dataCheck['passwd'] = array('type' => 'password', 'db' => 'users', 'err' => false);
			}

			/* Loop thru $_POST and sanitize user input. If ok
			 * add the data to query.
			*/
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
			$sql = substr($sql, 0, strlen($sql)-2) . " WHERE ".DBPREFIX."users.uid = '". $_POST['uid']."' AND ".DBPREFIX."userdata.uid = '". $_POST['uid']."'";

			if ((dbQuery($sql) === true) and (!$hasError)) {
				
				echo '<div class="alert alert-success"><strong>'._('User information updated').'</strong></div>';
			} else {
				echo '<div class="alert alert-danger"><strong>'._('An error occurred during update').'</strong></div>';
			}
    }	
	}

	echo isset($_GET['u']) ? '<div class="alert alert-success"><strong>'._('User information updated').'</strong></div>' : '';
?>

<div class='container-fluid'>
<h3>
<span class="bi bi-award-fill"></span>
Edit user</h3>
<form id="userselect" action="?page=edituser" method="post">
 <label for="find">Select user</label> <br>
 <input list="users" name="user">
 <datalist id="users">
<?php 
foreach ($gt as $row) {
echo '<option value="'.$row['username'].'">';
}
?>
</datalist> 
<button onClick="document.getElementById('userselect').submit()" class="btn btn-outline-primary" type="submit"><?php echo _('Fetch user data') ?></button>
                 
</form>
<?php 
if (isset($hasUserInfo)) {
	?>
<form id="userForm" action="?page=edituser" method="post">
	<input type="hidden" name="uid" value="<?php echo $queryUser[0]['uid']?>">
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
					<input class="form-control" type="text" name="username" placeholder="<?php echo _('Enter a username') ?>" value="<?php echo $queryUser[0]['username']?>" required>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('E-mail') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-envelope-fill"></span>
					<input class="form-control" type="text" name="email" placeholder="<?php echo _('Enter your e-mail') ?>" value="<?php echo $queryUser[0]['email']?>" required>
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
			echo _('Enter user information below.');
			?>
		</i></p>
		<div class="row justify-content-start">
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('First name') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill"></span>
					<input class="form-control" type="text" name="fname" placeholder="<?php echo _('Your first name') ?>" value="<?php echo $queryUser[0]['fname']?>" required>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="formlabel"><?php echo _('Last name') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-person-fill"></span>
					<input class="form-control" type="text" name="lname" placeholder="<?php echo _('Your last name') ?>" value="<?php echo $queryUser[0]['lname']?>" required>
				</div>
			</div>
		</div>

		<div class="row justify-content-start">
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Date of birth') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-calendar-event-fill"></span>
					<input class="form-control" type="date" name="birthdate" value="<?php echo $queryUser[0]['birthdate']?>" required>
				</div>
			</div>
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Length') ?></label>
				<div class="input-group" style="">
					<span class="input-group-text bi bi-rulers"></span>
					<input class="form-control" type="number" name="height" placeholder="<?php echo _('Length (cm)') ?>" min="50" max="230" value="<?php echo $queryUser[0]['height']?>" required>
					<span class="input-group-text">cm</span>
				</div>
			</div>
			<div class="col-sm-4">
				<label class="formlabel"><?php echo _('Gender') ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-gender-ambiguous"></span>
					<select class="form-select" name="sex">
						<?php
							echo '<option value="1"'.(($queryUser[0]['sex'] == 1) ? ' selected' : '').'>'._('Male').'</option>';
							echo '<option value="2"'.(($queryUser[0]['sex'] == 2) ? ' selected' : '').'>'._('Female').'</option>';
							echo '<option value="3"'.(($queryUser[0]['sex'] == 3) ? ' selected' : '').'>'._('Other').'</option>';
						?>
					</select>
				</div>
			</div>
		</div>
	
		<div class="line"></div>
		<button class="btn btn-primary" type="submit"><?php echo _('Save changes') ?></button>
		<button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#confirm"><?php echo _('Delete user') ?></button>
		<div class="modal fade" id="confirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete user?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete user <?php echo $queryUser[0]['username']?>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>
	</div>
</form>
<?php 
}
?>
