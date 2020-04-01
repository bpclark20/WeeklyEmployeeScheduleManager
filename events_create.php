<?php

#include helper php file
require 'pageWriter.php';

checkLoggedIn();

if ( !empty($_POST)) {
	// keep track validation errors
	$dateError = null;
	$timeError = null;
	$locationError = null;
	$descriptionError = null;
	$uniformError = null;
	
	// keep track post values
	$date = $_POST['date'];
	$time = $_POST['time'];
	$location = $_POST['location'];
	$description = $_POST['description'];
	$uniform = $_POST['uniform'];

	
	// validate input
	$valid = true;
	if (empty($date)) {
		$dateError = 'Please enter a Date.';
		$valid = false;
	}
	
	if (empty($time)) {
		$timeError = 'Please enter a time.';
		$valid = false;
	} 
	if (empty($location)) {
		$locationError = 'Please enter an event location.';
		$valid = false;
	}
	if (empty($description)) {
		$descriptionError = "Please enter an event description.";
		$valid = false;
	}
	if (empty($description)) {
		$descriptionError = "Please enter an event description.";
		$valid = false;
	}

	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO events (eventDate,eventTime,location,description,uniform) values(?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($date, $time, $location, $description, $uniform));
		Database::disconnect();
		header("Location: events_list.php");
	}
}	

writeHeader("Add a new event");
writeBodyOpen();
?>

<div class="span10 offset1">
    				<div class="row">
		    			<h3>Add a new Event</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="events_create.php" method="post">
					  <div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					    <label class="control-label">Date</label>
					    <div class="controls">
					      	<input name="date" type="date"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
					      	<?php if (!empty($dateError)): ?>
					      		<span class="help-inline"><?php echo $dateError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($timeError)?'error':'';?>">
					    <label class="control-label">Time</label>
					    <div class="controls">
					      	<input name="time" type="time" placeholder="Time" value="<?php echo !empty($time)?$time:'';?>">
					      	<?php if (!empty($timeError)): ?>
					      		<span class="help-inline"><?php echo $timeError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($locationError)?'error':'';?>">
					    <label class="control-label">Location</label>
					    <div class="controls">
					      	<input name="location" type="text"  placeholder="Location" value="<?php echo !empty($location)?$location:'';?>">
					      	<?php if (!empty($locationError)): ?>
					      		<span class="help-inline"><?php echo $locationError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					    <label class="control-label">Description</label>
					    <div class="controls">
					      	<input name="description" type="text"  placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
					      	<?php if (!empty($descriptionError)): ?>
					      		<span class="help-inline"><?php echo $descriptionError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
						<div class="control-group <?php echo !empty($uniformError)?'error':'';?>">
					    <label class="control-label">Uniform</label>
					    <div class="controls">
					      	<input name="uniform" type="text"  placeholder="Event Uniform" value="<?php echo !empty($uniform)?$uniform:'';?>">
					      	<?php if (!empty($uniformError)): ?>
					      		<span class="help-inline"><?php echo $uniformError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Add</button>
						  <a class="btn btn-primary" href="events_list.php">Back</a>
						</div>
					</form>
				</div>

<?php writeClosingTags(); ?>