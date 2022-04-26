<?php
	echo '<h2>'._('Add new measurements').'</h2>';
	echo '<p><i>'._('Multiple measurements can be added at the same time. Enter the values in the sections below.').'</p></i>';

	$data = dbFetch("SELECT ha_healthtype.name as type,ha_category.name as category,ha_units.name_short,ha_units.name_long FROM ha_healthtype	LEFT JOIN ha_category ON ha_healthtype.category = ha_category.catid	LEFT JOIN ha_units ON ha_healthtype.unit = ha_units.unitid");

	foreach ($data as $cat) {
		$list[$cat['category']][] = $cat;
	}

	$tabList = '<ul class="nav nav-tabs" id="myTab" role="tablist">';
	$tabContent = '<div class="tab-content" id="tabContent">';
	foreach ($list as $key=>$item) {
		#active class for active tab
		$tabList .= '<li class="nav-item" role="presentation"><button class="nav-link" id="'.$key.'-tab" data-bs-toggle="tab" data-bs-target="#'.$key.'" type="button" role="tab" aria-controls="'.$key.'" aria-selected="true">'.$key.'</button></li>';
		#show active class for active tabs
		$tabContent .= '<div class="tab-pane fade" id="'.$key.'" role="tabpanel" aria-labelledby="'.$key.'-tab">';
		$tabContent .= '<div class="d-flex align-items-start"><div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">';
		$tabContentInner = '<div class="tab-content" id="v-pills-tabContent">';
		foreach ($item as $content) {
			$tabContent .= '<button class="nav-link" id="v-pills-'.$content['type'].'-tab" data-bs-toggle="pill" data-bs-target="#v-pills-'.$content['type'].'" type="button" role="tab" aria-controls="v-pills-'.$content['type'].'" aria-selected="false">'.$content['type'].'</button>';
			$tabContentInner .= '<div class="tab-pane fade" id="v-pills-'.$content['type'].'" role="tabpanel" aria-labelledby="v-pills-'.$content['type'].'-tab">';
			$tabContentInner .= $content['type'];
			$tabContentInner .= '</div>';
		}
		$tabContent .= '</div>'. $tabContentInner.'</div></div></div>';
	}

	echo $tabList .'</ul>'. $tabContent . '</div>';
?>

