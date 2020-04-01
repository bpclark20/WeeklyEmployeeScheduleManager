<?php

#include helper php file
require 'pageWriter.php';

$id = null;

checkLoggedIn();

if ( !empty($_GET['id'])) {
	$id = $_REQUEST['id'];
}

if (null==$id) {
	header('Location: dashboard.php');
}

$LoggedInID = $_SESSION['employee_id'];

if ( !empty($_POST)) {
	// keep track validation errors
	$descriptionError = null;
	$locationError = null;
	$dateError = null;
	$timeError = null;
	$uniformError = null;

	# Initialize POST Variables
	$description = $_POST['description'];
	$location = $_POST['location'];
	$eventDate = $_POST['eventDate'];
	$eventTime = $_POST['eventTime'];
	$uniform = $_POST['uniform']; 

	# Validate User Input
	$valid = true;
	if (empty($description)) {
		$descriptionError = 'Please enter an event Name.';
		$valid = false;
	}

	if (empty($location)) {
		$locationError = 'Please enter an event location.';
		$valid = false;
	}

	if (empty($eventDate)) {
		$dateError = 'Please enter an event date.';
		$valid = false;
	} 

	if (empty($eventTime)) {
		$timeError = 'Please enter an event time.';
		$valid = false;
	}

	if (empty($uniform)) {
		$uniformError = 'Please enter a uniform description.';
		$valid = false;
	}

	// update data
	if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE events set description = ?, location = ?, eventDate = ?, eventTime = ?, uniform = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($description, $location, $eventDate, $eventTime, $uniform, $id));
			Database::disconnect();
			header("Location: events_list.php");
	}
} 
else {
	# Grab the info for the event we are currently trying to update
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM events where id = ? LIMIT 1";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$id = $data['id'];
	$description = $data['description'];
	$location = $data['location'];
	$eventDate = $data['eventDate'];
	$eventTime = $data['eventTime'];
	$uniform = $data['uniform'];
	Database::disconnect();

	# Grab the credentials of the user who is currently trying to 
	# make the update
	$LoggedInEmployeeTitle = getLoggedInUserTitle($LoggedInID);
}

writeHeader("Update Event Info");
writeBodyOpen();
?>

<div class="span10 offset1">
    				<div class="row">
		    			<h2>Update Event Info</h2>
		    		</div>
    		
	    			<form class="form-horizontal" action="events_update.php?id=<?php echo $id?>" method="post">
					  <div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					    <label class="control-label">Event</label>
					    <div class="controls">
					      	<input name="description" type="text"  placeholder="Event Description" value="<?php echo !empty($description)?$description:' ';?>" required>
					      	<?php if (!empty($descriptionError)): ?>
					      		<span class="help-inline"><?php echo $descriptionError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($locationError)?'error':'';?>">
					    <label class="control-label">Event Location</label>
					    <div class="controls">
					      	<input name="location" type="text"  placeholder="Event Location" value="<?php echo !empty($location)?$location:'';?>" required>
					      	<?php if (!empty($locationError)): ?>
					      		<span class="help-inline"><?php echo $locationError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					    <label class="control-label">Event Date</label>
					    <div class="controls">
					      	<input name="eventDate" type="date" value="<?php echo !empty($eventDate)?$eventDate:'';?>" required>
					      	<?php if (!empty($dateError)): ?>
					      		<span class="help-inline"><?php echo $dateError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
						<div class="control-group <?php echo !empty($timeError)?'error':'';?>">
					    <label class="control-label">Event Time</label>
					    <div class="controls">
					      	<input name="eventTime" type="time" value="<?php echo !empty($eventTime)?$eventTime:'';?>" required>
					      	<?php if (!empty($timeError)): ?>
					      		<span class="help-inline"><?php echo $timeError;?></span>
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
						  <button type="submit" class="btn btn-success">Update</button>
						  <a class="btn btn-warning" href="events_list.php">Back</a>
						</div>
					</form>
				</div>
<?php writeClosingTags(); ?>