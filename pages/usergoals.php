//Erik gör denna :) 

<?php
/*if ($_POST) {
foreach ($_POST as $value) {
		$x = verifyData($value,'int',false);
		echo $x.'<br>';
		
$sql = "INSERT INTO ha_goaltype (Workout, Sleep, Steps, Distance, Powernap, Calories, Weight)
				VALUES (
								foreach 
if (dbQuery($sql) === true ) {
    echo "Success! Your goals are now set.";
} else {
    ... on error do something
}
}


die;
}
*/

$gt =  dbFetch("SELECT * FROM ha_goaltype;");

$iconArray = array(
	'Weight' => 'bi-rulers',
	'Workout' => 'bi-heart-pulse',
	'Sleep' => 'bi-clock',
	'Steps' => 'bi-pin-map-fill',
	'Distance' => 'bi-pin-map-fill',
	'Powernap' => 'bi-clock',
	'Calories' => 'bi-activity',
	);
//die;
?>



<div class='container-fluid'>
	<h3>
	<span class="bi bi-award-fill"></span>
	Enter your goals</h3>
	<form id="GoalForm" action="?page=usergoals" method="post">
	<?php
	foreach ($gt as $row) {
    echo "<label class='formlabel'>" . _($row['name']) . "</label>"; 
		echo "<div class='input-group'>";
		echo "<span class='input-group-text bi ". $iconArray[$row['name']]."'></span>";
		echo "<input class='form-control' type='number' name='".strtolower($row['name'])."' placeholder='". _('Enter') ." ".strtolower(_($row['name'])). "'></input><br>"; 
		echo "</div>";
		
}
?>
			<br>
			<input type="submit" class="btn btn-success" value="<?php echo _('Save goals');?>">
			
	</form>
</div>
