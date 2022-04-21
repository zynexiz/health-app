//Erik gör denna :) 


<?php





?>






<div class='container'>
	<div class="p-3 primary text-black text-center">
	<span class="bi bi-award-fill"></span>
		<h3>Enter your goals</h3>
	</div>
	<form id="GoalForm" action="?page=usergoals.php" method="post">
	<label><h5><strong>Weight</strong></h5></label>
	<div class="col-sm-6">
		<div class="input-group">
			<span class="input-group-text bi bi-rulers"></span>
				<input class="form-control" type="number" name="Weight" placeholder="Enter your weight goal"</input>
		</div>
		</div>
		<br>
		
		
			<label><h5><strong>Steps per day</strong></h5></label>
			<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-text bi bi-pin-map-fill"></span>
					<input class="form-control" type="number" name="Steps" placeholder="Enter your steps per day goal"</input>
			</div>
			</div>
			<br>
			
			<label><h5><strong>Calories</strong></h5></label>
			<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-text bi bi-activity"></span>
					<input class="form-control" type="number" name="Steps" placeholder="Enter your calorie goal"</input>
			</div>
			</div>
			<br>
			
			<label><h5><strong>Sleep</strong></h5></label>
			<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-text bi bi-clock"></span>
					<input class="form-control" type="number" name="Steps" placeholder="Enter your sleep goal"</input>
			</div>
			</div>
			<br>
			
			<label><h5><strong>Workout</strong></h5></label>
			<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-text bi bi-heart-pulse"></span>
					<input class="form-control" type="number" name="Steps" placeholder="Enter your goal for workouts per week"</input>
			</div>
			</div>
			<br>
			
			<label><h5><strong>Distance</strong></h5></label>
			<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-text bi bi-pin-map-fill"></span>
					<input class="form-control" type="number" name="Steps" placeholder="Enter your goal for desired distanceS"</input>
			</div>
			</div>
			<br>
			
			<input type="submit" class="btn btn-success" value="Submit Button">
			
	</form>
</div>
