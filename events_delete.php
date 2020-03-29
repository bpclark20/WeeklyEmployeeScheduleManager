<?php

#include helper php file
require 'pageWriter.php';

checkLoggedIn();

$id = 0;
	
if ( !empty($_GET['id'])) {
	$id = $_REQUEST['id'];
}

if ( !empty($_POST)) {
	// keep track post values
	$id = $_POST['id'];
	
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM events WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: events_list.php");
}
else { //Otherwise prepopulate the date fields and bring up the details of the selected event to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	# Get details of person
	$sql = "SELECT * FROM events where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);

	$description = $data['description'];
	$location = $data['location'];
	$eventDate = $data['eventDate'];
	$eventTime = $data['eventTime'];
	$uniform = $data['uniform'];

	Database::disconnect();
}

writeHeader("Delete an Event");
writeBodyOpen();
?>
<div class="span10 offset1">
	<div class="row">
		<h2>Delete an Event</h2>
	</div>
		    		
	<form class="form-horizontal" action="events_delete.php" method="post">
		<input type="hidden" name="id" value="<?php echo $id;?>"/>
<div class="form-horizontal">
					  <div class="control-group">
					    <h4>Event</h4>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php echo $description;?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Event Location</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $location;?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Event Date</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $eventDate;?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Event Time</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $eventTime;?>
						    </label>
					    </div>
					  </div>
						<div class="control-group">
					    <h4>Uniform</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $uniform;?>
						    </label>
					    </div>
					  </div>

		  <p class="alert alert-error">Are you sure you want to delete this event?</p>
	    <div class="form-actions">
			<button type="submit" class="btn btn-danger">Yes</button>
			<a class="btn btn-primary" href="events_list.php">No</a>
		</div>
	</div>
	</form>
</div>

<?php writeClosingTags(); ?>