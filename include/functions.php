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
	$domain = "messages";
	$ret = bindtextdomain($domain, "./assets/lang");
	bind_textdomain_codeset($domain, 'UTF-8');
	textdomain($domain);
	return $ret;
}

function dbConnect() {
	// Create connection
	global $CONFIG;
	$conn = new mysqli($CONFIG['dbhost'], $CONFIG['dbuser'], $CONFIG['dbpassword'], $CONFIG['dbname'], $CONFIG['dbport']);
	// Check connection
	if ($conn->connect_error) {
		die("DB Connection failed: " . $conn->connect_error);
	}
	$conn->set_charset('utf8mb4');
	return $conn;
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
		$err_msg = '<div class="alert alert-danger"><strong>A problem occurred!</strong><br><br>';
		$err_msg .= (isset($error_body)) ? $error_body : 'Data verification test failed.</div>';
		echo $err_msg;
		die;
	}

	return (isset($result[0])?implode(',',$result[0]):false);
}
?>
