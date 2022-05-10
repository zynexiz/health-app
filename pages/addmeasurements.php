<?php
	# Fetch data from database and create an array of the healthtype data
	$data = dbFetch("SELECT ha_healthtype.typeid, ha_healthtype.name as type,ha_category.name as category,ha_units.name_short,ha_units.name_long,ha_units.unittype FROM ha_healthtype	LEFT JOIN ha_category ON ha_healthtype.category = ha_category.catid	LEFT JOIN ha_units ON ha_healthtype.unit = ha_units.unitid");
	$intesity = dbFetch("SELECT * FROM ha_intensity");
	foreach ($data as $cat) {
		$list[$cat['category']][] = $cat;
	}

	if ($_POST) {
		$sql = "INSERT INTO ha_healthdata (uid, healthtype, amount, timestart, timeend, intensity) VALUES ";
		foreach ($list as $key=>$item) {
			foreach ($item as $content) {
				# Check if entry is of "time type", then calculate the duration from start and end time.
				if ($content['unittype'] == "time") {
					$firstTime=strtotime($_POST[$content['type'].'-timestart']);
					$lastTime=strtotime($_POST[$content['type'].'-timestop']);
					$_POST[$content['type']] = ($lastTime-$firstTime > 0) ? (($content['name_short'] == "h") ? (($lastTime-$firstTime)/3600) : (($lastTime-$firstTime)/60) ) : '';
				}

				# If amount is entered, the add it to query.
				if ($_POST[$content['type']]) {
					$_POST[$content['type'].'-intesity'] = ($key == "Workout") ? $_POST[$content['type'].'-intesity'] : "null";
					$sql .= "('".$_SESSION['id']."','".$_POST[$content['type'].'-typeid']."',".number_format($_POST[$content['type']], 2, '.', '').",'".date('Y-m-d H:i', strtotime($_POST[$content['type'].'-timestart']))."','".date('Y-m-d H:i', strtotime($_POST[$content['type'].'-timestop']))."',".$_POST[$content['type'].'-intesity']."),";
				}
			}
		}
		$r = dbQuery(substr($sql, 0, strlen($sql)-1));
		if ($r !== true) {
			echo $r;
		}
	}

	echo '<h2>'._('Add new measurements').'</h2>';
	echo '<p><i>'._('Multiple measurements can be added at the same time. Enter the values in the sections below.').'</p></i>';
	echo '<form id="measurements" action="?page=addmeasurements" method="post">';

	/* Build the form from the healthtype array
	 *  $tabList					The tabs for each healthtype (workout, helth etc)
	 *  $tabContent 			The content of each tab
	 *  $tabContentInner	The content of each subtab (pills) containing the forms
	 */
	$tabList = '<ul class="nav nav-tabs" id="myTab" role="tablist">';
	$tabContent = '<div class="tab-content boxed" id="tabContent">';
	foreach ($list as $key=>$item) {
		#active class for active tab
		$tabList .= '<li class="nav-item active" role="presentation"><button class="nav-link" id="'.$key.'-tab" data-bs-toggle="tab" data-bs-target="#'.$key.'" type="button" role="tab" aria-controls="'.$key.'" aria-selected="true">'.$key.'</button></li>';
		#show active class for active tabs
		$tabContent .= '<div class="tab-pane fade" id="'.$key.'" role="tabpanel" aria-labelledby="'.$key.'-tab">';
		$tabContent .= '<div class="d-flex align-items-start"><div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">';
		$tabContentInner = '<div class="tab-content" id="v-pills-tabContent">';
		foreach ($item as $content) {
			$tabContent .= '<button class="nav-link" id="v-pills-'.$content['type'].'-tab" data-bs-toggle="pill" data-bs-target="#v-pills-'.$content['type'].'" type="button" role="tab" aria-controls="v-pills-'.$content['type'].'" aria-selected="false">'.$content['type'].'</button>';
			$tabContentInner .= '<div class="tab-pane fade" id="v-pills-'.$content['type'].'" role="tabpanel" aria-labelledby="v-pills-'.$content['type'].'-tab">';
			$tabContentInner .= <<<HTML
				<input type="hidden" name="{$content['type']}-typeid" value="{$content['typeid']}">
				<h3>{$content['type']}</h3>
				<div class="container float-start">
					<div class="row justify-content-start">
						<div class="col-sm-6">
							<label class="formlabel">From time</label>
							<div class="input-group">
								<span class="input-group-text bi bi-envelope-fill"></span>
								<input class="form-control" type="datetime-local" name="{$content['type']}-timestart">
							</div>
						</div>
						<div class="col-sm-6">
							<label class="formlabel">To time</label>
							<div class="input-group">
								<span class="input-group-text bi bi-envelope-fill"></span>
								<input class="form-control" type="datetime-local" name="{$content['type']}-timestop">
							</div>
						</div>
					</div>
				<div class="row justify-content-start">
HTML;
			if ($content['unittype'] != "time") {
			$tabContentInner .= <<<HTML
					<div class="col-sm-6">
						<label class="formlabel">Enter {$content['unittype']} in {$content['name_long']}</label>
						<div class="input-group">
							<span class="input-group-text bi bi-person-fill"></span>
							<input class="form-control" type="number" name="{$content['type']}" placeholder="{$content['name_long']}" value="">
						</div>
					</div>
HTML;
			}
			if ($content['category'] == "Workout") {
				$tabContentInner .= <<<HTML
				<div class="col-sm-6">
					<label class="formlabel">Select intesity</label>
					<div class="input-group">
						<span class="input-group-text bi bi-person-fill"></span>
						<select class="form-select" name="{$content['type']}-intesity">
HTML;
 				foreach ($intesity as $select) {
					$tabContentInner .= ($select['typeid'] == $content['typeid']) ? '<option value="'.$select['iid'].'">'.$select['name'].'</option>' : '';
				}
				$tabContentInner .= <<<HTML
						</select>
					</div>
				</div>
HTML;
			}
			$tabContentInner .= '</div></div></div>';
		}
		$tabContent .= '</div>'. $tabContentInner.'</div></div></div>';
	}

	echo $tabList .'</ul>'. $tabContent . '</div>';
	echo '<br><button class="btn btn-primary" type="submit">' . _('Add measurements') .'</button>';
	echo '</form>';

?>

