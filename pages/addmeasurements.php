<?php
	echo '<h2>'._('Add new measurements').'</h2>';
	echo '<p><i>'._('Multiple measurements can be added at the same time. Enter the values in the sections below.').'</p></i>';

	$data = dbFetch("SELECT ha_healthtype.name as type,ha_category.name as category,ha_units.name_short,ha_units.name_long FROM ha_healthtype	LEFT JOIN ha_category ON ha_healthtype.category = ha_category.catid	LEFT JOIN ha_units ON ha_healthtype.unit = ha_units.unitid");

	foreach ($data as $cat) {
		$list[$cat['category']][] = $cat;
	}
var_dump($list);
	$selectedCat = isset($_POST['cat']) ? $_POST['cat'] : $cat[0]['name'];


	echo '<ul class="nav nav-tabs" id="myTab" role="tablist">';
	echo $tabs;
	echo '</ul>';
?>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="Health" role="tabpanel" aria-labelledby="home-tab">
		<div class="d-flex align-items-start">
			<div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
				<?php

				?>
				<button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</button>
				<button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</button>
				<button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</button>
				<button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</button>
			</div>

			<div class="tab-content" id="v-pills-tabContent">
				<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"><?php var_dump($hType);?></div>
				<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
				<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
				<div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
			</div>
		</div>
  </div>
  <div class="tab-pane fade" id="Workout" role="tabpanel" aria-labelledby="profile-tab">Tab 2</div>
  <div class="tab-pane fade" id="Food" role="tabpanel" aria-labelledby="contact-tab">Tab 3</div>
</div>
