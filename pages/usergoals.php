<?php
$gt =  dbFetch("SELECT * FROM ha_goaltype;");
if ($_POST) {
	$sql = "INSERT INTO ha_goals (gtid, uid, goal) VALUES (";
	var_dump($_POST);

	foreach ($gt as $value) {
		if($_POST[$value['name']]) {
			$sql = "INSERT INTO ha_goals (gtid, uid, goal) VALUES (";
			$x = verifyData($_POST[$value['name']],'int');
			$sql.= $_SESSION["id"].','.$x.',';
		}
	}
	//Substring returns part of a string, in this case the first position of the string and the whole length of it and also removes the coma.
	$sql = substr($sql,0,strlen($sql)-1).')';
	echo $sql;
	//die;





	//If the database query is true it will echo "Success", else it will do something.
	if (dbQuery($sql) === true ) {
		echo "Success! Your goals are now set.";
	} else {
		echo "Something went wrong! Sorry :-(";
	}
}
//die;

//Array with icons for each activity. Weight = Key, bi = Value.
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
//Itirates over every row(activity) from the DB and prints the name.
foreach ($gt as $row) {
	echo "<label class='formlabel'>" . _($row['name']) . "</label>";
	echo "<div class='input-group'>";
	echo "<span class='input-group-text bi ". $iconArray[$row['name']]."'></span>";
	echo "<input class='form-control' type='number' name='".$row['name']."' placeholder='". _('Enter') ." ".strtolower(_($row['name'])). "'></input><br>";
	echo "</div>";

}
?>
<br>
<input type="submit" class="btn btn-success" value="<?php echo _('Save goals');?>">

</form>
</div>
