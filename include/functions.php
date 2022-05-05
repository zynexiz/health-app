<?php
/* Set the global language
 *
 *  setLanguage(str $language)
 */
function setLanguage($language) {
	$language .= ".UTF8";
	putenv("LANG=".$language);
	putenv("LANGUAGE=".$language);
	setlocale(LC_ALL, $language);
	$domain = "messages1";
	$ret = bindtextdomain($domain, "./assets/lang");
	bind_textdomain_codeset($domain, 'UTF-8');
	textdomain($domain);
	return $ret;
}

/* Fetch data from database from a given SQL query
 *
 *  dbFetch(str $query)
 */
function dbFetch($query) {
	$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME, DBPORT);
	/* Die if connection can't be established, else fetch query
	 * and return an array with the data, or empty array if query
	 * returns empty result.
	 */
	if ($conn->connect_error) {
		die("DB Connection failed: " . $conn->connect_error);
	}
	$conn->set_charset('utf8mb4');
	$result = $conn -> query($query);
	$row = $result -> fetch_all(MYSQLI_ASSOC);
	$result -> free_result();
	$conn -> close();
	return $row;
}

/* Execute a SQL query (insery, update etc) and return "ok" if everything worked or error string.
 *
 *  dbQuery(str $query)
 */
function dbQuery($query) {
	$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME, DBPORT);
	/* Die if connection can't be established, else fetch query
	 * and return an array with the data, or empty array if query
	 * returns empty result.
	 */
	if ($conn->connect_error) {
		die("DB Connection failed: " . $conn->connect_error);
	}
	$conn->set_charset('utf8mb4');
	if ($conn->query($query) === TRUE) {
		$result = true;
	} else {
		$result = $conn->error;
	}
	return $result;
}

function addLogData() {
	$uagent = $_SERVER['HTTP_USER_AGENT'];

	# Get browser name
	if(strpos($uagent, 'MSIE') !== false)
		$browser = 'Internet Explorer';
	elseif(strpos($uagent, 'Trident') !== false)
		$browser = 'Internet Explorer';
	elseif(strpos($uagent, 'Firefox') !== false)
		$browser = 'Mozilla Firefox';
	elseif(strpos($uagent, 'Edge') !== false)
		$browser = 'Microsoft Edge';
	elseif(strpos($uagent, 'Chrome') !== false)
		$browser = 'Google Chrome';
	elseif(strpos($uagent, 'Opera') !== false)
		$browser = "Opera";
	elseif(strpos($uagent, 'Safari') !== false)
		$browser = "Safari";
	else
		$browser = 'Other';

	# Get the operating_system name
	$operating_system = 'Other';
	if (preg_match('/linux/i', $uagent)) {
		$operating_system = 'Linux';
	} elseif (preg_match('/windows|win32|win98|win95|win16/i', $uagent)) {
		$operating_system = 'Windows';
	} elseif (preg_match('/ubuntu/i', $uagent)) {
		$operating_system = 'Ubuntu';
	} elseif (preg_match('/iphone/i', $uagent)) {
		$operating_system = 'IPhone';
	} elseif (preg_match('/ipad/i', $uagent)) {
		$operating_system = 'IPad';
	} elseif (preg_match('/macintosh|mac os x|mac_powerpc/i', $uagent)) {
		$operating_system = 'Mac OS';
	} elseif (preg_match('/android/i', $uagent)) {
		$operating_system = 'Android';
	} elseif (preg_match('/blackberry/i', $uagent)) {
		$operating_system = 'Blackberry';
	} elseif (preg_match('/webos/i', $uagent)) {
		$operating_system = 'Mobile';
	}

	# Get client IP address and request time and URI
	$ip = $_SERVER['REMOTE_ADDR'];
	$timedate = date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME']);
	$uri = $_SERVER['REQUEST_URI'];

	$query = "INSERT INTO ha_logdata (ip, browser, platform, timedate, page) VALUES ('{$ip}', '{$browser}', '{$operating_system}', '{$timedate}', '{$uri}')";
	$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME, DBPORT);
	$conn->set_charset('utf8mb4');
	if ($conn->connect_error) {
		die("DB Connection failed: " . $conn->connect_error);
	}
	$conn->query($query);

	if ($_SESSION['role'] > 0) {
		$logid = $conn->insert_id;
		$query = "INSERT INTO ha_userlog (uid, ldid) VALUES ('{$_SESSION['id']}', '{$logid}')";
		$result = dbQuery($query);
	}
}

/* Creates a chartjs graph displaying data.
 *  str $id = unique canvas element ID
 *  dataset $labels = labels for y axis (fx. [1,2,3...]
 *  array $data = array of data for x axis
 *   fx: array(array('label' => 'label', 'data' => [1,2,...], 'lineColor' => 'r,g,b,a', 'type' => 'line/bar'), array(next data..),..)
 *
 *  function drawChart(str $id, dataset $labels, array $data)
 */
function drawChart($id, $labels, $data) {
	$labelData = implode("','",$labels);
	$chartData = <<<STR
	<canvas id="{$id}"></canvas>
	<script>
		new Chart("{$id}", {

		data: {
			labels: ['{$labelData}'],
			datasets: [
STR;
	foreach ($data as $set) {
		$dataList = implode(',',$set['data']);
		$chartData .= <<<STR
			{
				label: '{$set['label']}',
				type: "{$set['type']}",
				fill: false,
				lineTension: 0.25,
				backgroundColor: 'rgba({$set['lineColor']})',
				borderColor: 'rgba({$set['lineColor']})',
				data: [{$dataList}]
			},
STR;
	}
$chartData .= <<<STR
		]},
		options: {
			responsive: true,
			legend: {display: false},
			scales: {
				borderWidth: 5,
				xAxes: {
					ticks: {min: 6, max:9},
					grid: {
						display: false,
						drawBorder: true,
						color: "#ff0000",
						borderColor: "#eee"
					}
				},
				yAxes: {
					grid: {
						display: false,
						drawBorder: true,
						color: "#ff0000",
						borderColor: "#eee"
					}
				}
			},

		}
	});
	</script>
STR;
echo $chartData;
}

/* Verifying data for sanitization. Returns validated data or false on error if $abort_on_error set to false.
 * Default aborts script on validation failure.
 *
 *  verifyData( str $data, str $type, bool $abort_on_error)
 */
function verifyData( $data, $type, $abort_on_error = true) {
	switch ($type) {
		case 'page':
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
		case 'username':
			$regex = '/^(?=.{3,25}$)[a-z0-9]+(?:[._-][a-z0-9]+)*[._-]?$/';
			break;
		case 'date':
			$regex = '/^(19[0-9]{2}|2[0-9]{3})\-(0[1-9]|1[0-2])\-(0[1-9]|1[0-9]|2[0-9]|3[0-1])((T|\s)(0[0-9]{1}|1[0-9]{1}|2[0-3]{1})\:(0[0-9]{1}|1[0-9]{1}|2[0-9]{1}|3[0-9]{1}|4[0-9]{1}|5[0-9]{1})\:(0[0-9]{1}|1[0-9]{1}|2[0-9]{1}|3[0-9]{1}|4[0-9]{1}|5[0-9]{1})((\+|\.)[\d+]{4,8})?)?$/';
			break;
		case 'ipaddress';
			$regex = '/^((\*)|((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|((\*\.)?([a-zA-Z0-9-]+\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,63}?))$/';
			break;
		/* Verify that password meats requirements
		 *
		 * It contains 8 - 30 characters.
		 * It contains at least one number.
		 * It contains at least one upper case character.
		 * It contains at least one lower case character.
    */
		case 'password';
			$regex = '#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#';
			break;
		default:
			echo '<div class="alert alert-danger"><strong>A problem occurred!</strong><br><br>Internal type check error: key <strong>'.$type.'</strong> not defined';
			die;
	}

	if (!preg_match_all($regex, $data, $result) && $abort_on_error) {
		$err_msg = '<div class="alert alert-danger"><strong>' . _('A problem occurred!') . '</strong><br><br>';
		$err_msg .= (isset($error_body)) ? $error_body : _('Data verification test failed.');
		echo $err_msg . '</div>';
		die;
	}

	return (isset($result[0])?implode(',',$result[0]):false);
}
?>
