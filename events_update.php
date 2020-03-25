<?php

#include helper php file
require 'pageWriter.php';

$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( null==$id ) {
		header("Location: crud_customers.php");
	}
	
	if ( !empty($_POST)) {
	// keep track validation errors
	$dateError = null;
	$timeError = null;
	$locationError = null;
	$descriptionError = null;
	
	// keep track post values
	$date = $_POST['date'];
	$time = $_POST['time'];
	$location = $_POST['location'];
	$description = $_POST['description'];

	
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
		
		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE events  set eventDate = ?, eventTime = ?, location =?, description = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($date,$time,$location,$description, $id));
			Database::disconnect();
			header("Location: crud_events.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM events where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$date = $data['eventDate'];
		$time = $data['eventTime'];
		$location = $data['location'];
		$description = $data['description'];
		Database::disconnect();
	}

writeHeader("CRUD - Events - Update an event");
writeBodyOpen();
?>

<div class="span10 offset1">
    				<div class="row">
		    			<h2>Update an Event</h2>
		    		</div>
    		
	    			<form class="form-horizontal" action="crud_events_update.php?id=<?php echo $id?>" method="post">
					  <div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					    <h4>Date</h4>
					    <div class="controls">
					      	<input name="date" type="text"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
					      	<?php if (!empty($dateError)): ?>
					      		<span class="help-inline"><?php echo $dateError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($timeError)?'error':'';?>">
					    <h4>Time</h4>
					    <div class="controls">
					      	<input name="time" type="text" placeholder="Time" value="<?php echo !empty($time)?$time:'';?>">
					      	<?php if (!empty($timeError)): ?>
					      		<span class="help-inline"><?php echo $timeError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($locationError)?'error':'';?>">
					    <h4>Location</h4>
					    <div class="controls">
					      	<input name="location" type="text"  placeholder="Location" value="<?php echo !empty($location)?$location:'';?>">
					      	<?php if (!empty($locationError)): ?>
					      		<span class="help-inline"><?php echo $locationError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					    <h4>Description</h4>
					    <div class="controls">
					      	<input name="description" type="text"  placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
					      	<?php if (!empty($descriptionError)): ?>
					      		<span class="help-inline"><?php echo $descriptionError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Update</button>
						  <a class="btn btn-warning" href="crud_events.php">Back</a>
						</div>
					</form>
				</div>
<?php writeClosingTags(); ?>