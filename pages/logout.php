<?php
	# Destroy the session and log out user
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
	header("Location: ".dirname($_SERVER['SCRIPT_NAME']));
?>
