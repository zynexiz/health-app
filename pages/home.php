<?php
$graphType = (isset($_POST['chartType'])) ? verifyData($_POST['chartType'], 'name', false) : 'line';
$startDate = (isset($_POST['startDate'])) ? verifyData($_POST['startDate'], 'date', false) : '0-0-0';
$endDate = (isset($_POST['endDate'])) ? verifyData(($_POST['endDate'] != '') ? $_POST['endDate'] : date("Y-m-d"), 'date', false) : date("Y-m-d");

$usrData = dbFetch("SELECT ha_healthdata.amount, ha_healthdata.timestart, ha_healthdata.timeend, ha_healthtype.name AS typename, ha_units.name_short, ha_units.name_long, ha_units.unittype, ha_intensity.kcal FROM ha_healthdata LEFT JOIN ha_healthtype ON ha_healthtype.typeid = ha_healthdata.healthtype LEFT JOIN ha_units ON ha_units.unitid = ha_healthtype.unit LEFT JOIN ha_intensity ON ha_intensity.iid = ha_healthdata.intensity WHERE ha_healthdata.uid = ".$_SESSION['id']." AND DATE(ha_healthdata.timestart) BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY ha_healthdata.timestart");
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
echo '<p><i>'._('Your health and workout statistics by category and day').'</i></p>';
?>
	<form id="graphselect" action="?page=home" method="post">
		<div class="row justify-content-start">
			<div class="col-sm-3">
				<label class="formlabel" for="find"><?php echo _('Select graph type'); ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-graph-down"></span>
					<select class="form-control" name="chartType">
						 <option value="line" <?php echo (($graphType == "line") ? 'selected' : '').'>'._('Line style'); ?></option>
						  <option value="bar" <?php echo (($graphType == "bar") ? 'selected' : '').'>'._('Bar style'); ?></option>
					</select>
				</div>
			</div>
			<div class="col-sm-3">
				<label class="formlabel" for="find"><?php echo _('Select start date'); ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-calendar-event"></span>
					<input class="form-control" type="date" name="startDate" value="<?php echo $startDate ?>">
				</div>
			</div>
			<div class="col-sm-3">
				<label class="formlabel" for="find"><?php echo _('Select end date'); ?></label>
				<div class="input-group">
					<span class="input-group-text bi bi-calendar-event"></span>
					<input class="form-control" type="date" name="endDate" value="<?php echo $endDate ?>">
				</div>
			</div>
			<div class="col-sm-3">
			<label class="formlabel"></label>
				<div class="input-group">
					<button onClick="document.getElementById('graphselect').submit()" class="btn btn-primary" type="submit"><?php echo _('Change') ?></button>
				</div>
			</div>
		</div>
	</form>
	<div class="line"></div>
<?php
if (isset($userStats)) {
	foreach ($hType as $type) {
		$ht = $type['healthtype'];
		foreach ($userStats as $date=>$stats) {
			if (isset($stats[$ht])) {
				if (!isset($chartData[$ht])) {
					$chartData[$ht]['chartdata'] = array(array('label'=>$ht, 'type'=>$graphType, 'data'=>[(float) $stats[$ht]['amount']]));
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
					<?php drawChart($data['dates'],$data['chartdata'],false); ?>
			</div>
		</div>
	</div>
	<?php }
} else {
	echo _('No statistics found for this period.');
}
?>
