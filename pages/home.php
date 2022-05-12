<?php
$usrData = dbFetch('SELECT ha_healthdata.amount, ha_healthdata.timestart, ha_healthdata.timeend, ha_healthtype.name AS typename, ha_units.name_short, ha_units.name_long, ha_units.unittype, ha_intensity.kcal FROM ha_healthdata LEFT JOIN ha_healthtype ON ha_healthtype.typeid = ha_healthdata.healthtype LEFT JOIN ha_units ON ha_units.unitid = ha_healthtype.unit LEFT JOIN ha_intensity ON ha_intensity.iid = ha_healthdata.intensity WHERE ha_healthdata.uid = '.$_SESSION['id'].' ORDER BY ha_healthdata.timestart');
$hType = dbFetch('SELECT ha_healthtype.name AS healthtype, ha_category.name AS category, ha_units.name_long, ha_units.unittype FROM ha_healthtype LEFT JOIN ha_category ON ha_healthtype.category = ha_category.catid LEFT JOIN ha_units ON ha_units.unitid = ha_healthtype.unit');

foreach ($usrData as $part) {
	$arrayDate = date('Y-m-d', strtotime($part['timestart']));
	$arrayKey = $part['typename'];
	foreach ($part as $key=>$data) {
		if (isset($userStats[$arrayDate][$arrayKey][$key]) && ($key == "kcal" || $key == "amount")) {
			$data += $userStats[$arrayDate][$arrayKey][$key];
		}
		$userStats[$arrayDate][$arrayKey][$key] = $data;
	}
}

echo '<h2>'._('Welcome ').$_SESSION['firstname'].'. '. _("Here's your statistics!").'</h2>';

if (isset($userStats)) {
	foreach ($hType as $type) {
		$ht = $type['healthtype'];
		foreach ($userStats as $date=>$stats) {
			if (isset($stats[$ht])) {
				if (!isset($chartData[$ht])) {
					$chartData[$ht]['chartdata'] = array(array('label'=>$ht, 'type'=>'line', 'data'=>[(float) $stats[$ht]['amount']]));
					$chartData[$ht]['dates'][] = $date;
					$chartData[$ht]['unittype'] = $type['unittype'];
					$chartData[$ht]['unit'] = $type['name_long'];
				} else {
						$chartData[$ht]['chartdata'][0]['data'][] = (float) $stats[$ht]['amount'];
						$chartData[$ht]['dates'][] = $date;
				}
			}
		}
	}
?>
<div class="container">
	<div class="row align-items-center">
	<?php	foreach ($chartData as $data) { ?>
		<div class="col">
			<div class="card text-center" style="margin-bottom: 20px">
				<div class="card-body mx-auto">
					<h5 class="card-title"><?php echo $data['chartdata'][0]['label'] ?></h5>
					<h6 class="card-subtitle text-muted"><?php echo $data['unittype'].' ('.$data['unit'].')'; ?></h6>
					<?php drawChart($data['dates'],$data['chartdata'],false, 800, 400); ?>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<?php
} else {
	echo _('No statistics found, add some first.');
}
?>
