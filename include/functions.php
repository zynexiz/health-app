<?php
function setLanguage($language) {
	putenv("LANG=".$language);
	putenv("LANGUAGE=".$language);
	setlocale(LC_ALL, $language);
	$domain = "messages";
	$ret = bindtextdomain($domain, "./assets/lang");
	bind_textdomain_codeset($domain, 'UTF-8');
	textdomain($domain);
	return $ret;
}

function verify_data( $data, $type, $abort_on_error = true) {
	switch ($type) {
		case 'page':
			$error_body = 'Error 404: Requested page not found.';
			$regex = (file_exists('pages/'.$data.'.php')?'/^('.$data.')$/':'/^$/');
			break;
		case 'email':
			$regex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
			break;
		case 'int':
			$regex = '/^[0-9]*$/';
			break;
		case 'name':
			$regex = '/^[^(){}:;+#?$^"%*!&£=\/~@0123456789]+$/';
			break;
		case 'ipaddress';
			$regex = '/^((\*)|((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|((\*\.)?([a-zA-Z0-9-]+\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,63}?))$/';
			break;
		default:
			echo '<div class="alert alert-danger"><strong>A problem occurred!</strong><br><br>Internal type check error: key <strong>'.$type.'</strong> not defined';
			die;
	}

	if (!preg_match_all($regex, $data, $result) && $abort_on_error) {
		$err_msg = '<div class="alert alert-danger"><strong>A problem occurred!</strong><br><br>';
		$err_msg .= (isset($error_body)) ? $error_body : 'Data verification test failed.</div>';
		echo $err_msg;
		die;
	}

	return (isset($result[0])?implode(',',$result[0]):false);
}
?>
