<h2><?php echo _('Log data') ?></h2>
<p><i>
<?php
	# Fetch different data from the logs and build the data for the charts
	$qBrowser = dbFetch('SELECT browser, COUNT(*) AS hits FROM ha_logdata GROUP BY browser');
	foreach ($qBrowser as $hits) {
		$browserData[] = $hits['hits'];
		$browserType[] = $hits['browser'];
	}
	$qSystem = dbFetch('SELECT platform, COUNT(*) AS hits FROM ha_logdata GROUP BY platform');
	foreach ($qSystem as $hits) {
		$systemData[] = $hits['hits'];
		$systemType[] = $hits['platform'];
	}
	$visitorsQuery = dbFetch('SELECT COUNT(DISTINCT ip) AS hits, DATE(timedate) AS date FROM ha_logdata WHERE DATE(timedate) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND DATE(timedate) <= CURDATE() GROUP BY date(timedate)');
	$visitors = array(array(
		'label'=>'Visitors',
		'type'=>'line',
		),
	);
	foreach ($visitorsQuery as $hits) {
		$visitors[0]['data'][] = $hits['hits'];
		$dateRange[] = $hits['date'];
	}
	$visitorsHourQuery = dbFetch('SELECT COUNT(DISTINCT ip) as hits, DATE_FORMAT(timedate, "%H") AS time FROM ha_logdata WHERE DATE(timedate) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND DATE(timedate) <= CURDATE() GROUP BY DATE_FORMAT(timedate, "%H")');
	$visitorsHour = array(array(
		'label'=>'Visitors',
		'type'=>'line',
	),
	);
	foreach ($visitorsHourQuery as $hits) {
		$visitorsHour[0]['data'][] = $hits['hits'];
		$dateRangeHour[] = $hits['time'];
	}
?>
</i></p>

<div class="container">
	<div class="row align-items-center">
		<div class="col">
			<div class="card text-center">
				<div class="card-body mx-auto">
					<h5 class="card-title"><?php echo _('Web browser'); ?></h5>
					<?php drawChart($browserType,$browserData,true, 300, 300); ?>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-body mx-auto">
					<h5 class="card-title"><?php echo _('OS platform'); ?></h5>
					<?php drawChart($systemType,$systemData,true, 300, 300); ?>
				</div>
			</div>
		</div>
	</div><br>
	<div class="row align-items-center">
		<div class="col">
			<div class="card text-center">
				<div class="card-body">
					<h5 class="card-title"><?php echo _('Unique visitors per day'); ?></h5>
					<?php drawChart($dateRange,$visitors,false, 250, null); ?>
				</div>
			</div>
		</div>
	</div><br>
	<div class="row align-items-center">
		<div class="col">
			<div class="card text-center">
				<div class="card-body">
					<h5 class="card-title"><?php echo _('Unique visitors last 24 hour') ?></h5>
					<?php drawChart($dateRangeHour,$visitorsHour,false, 250, null); ?>
				</div>
			</div>
		</div>
	</div><br>

	<?php $accessData = dbFetch('SELECT ha_users.uid, ha_users.username, ha_logdata.ip, MAX(ha_logdata.timedate) as time, COUNT(*) as hits FROM ha_userlog LEFT JOIN ha_users ON ha_users.uid = ha_userlog.uid	LEFT JOIN ha_logdata ON ha_logdata.ldid = ha_userlog.ldid	GROUP BY ha_userlog.uid ORDER BY ha_logdata.timedate DESC'); ?>
	<div class="row align-items-center">
		<div class="col">
			<div class="card text-center">
				<div class="card-body">
					<h5 class="card-title"><?php echo _('User access data');?></h5>
					<table class="table">
					<thead>
						<tr>
							<th scope="col">User ID</th>
							<th scope="col">User name</th>
							<th scope="col">Last IP</th>
							<th scope="col">Last access</th>
							<th scope="col"># of access</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($accessData as $data) {
								echo '<tr><th scope="row">'.$data['uid'].'</th><td>'.$data['username'].'</td><td>'.$data['ip'].'</td><td>'.$data['time'].'</td><td>'.$data['hits'].'</td><td>';
							}
						?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
?>
