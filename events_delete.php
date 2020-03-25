<?php

#include helper php file
require 'pageWriter.php';

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
	$sql = "DELETE FROM events  WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: crud_events.php");
}
else { //Otherwise prepopulate the date fields and bring up the details of the selected person to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	# Get details of person
	$sql = "SELECT * FROM events where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);

	Database::disconnect();
}

writeHeader("CRUD - Events - Delete an Event");
writeBodyOpen();
?>
<div class="span10 offset1">
	<div class="row">
		<h2>Delete an Event</h2>
	</div>
		    		
	<form class="form-horizontal" action="crud_events_delete.php" method="post">
		<input type="hidden" name="id" value="<?php echo $id;?>"/>

		<div class="form-horizontal" >
					  <div class="control-group">
					    <h4>Date</h4>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php echo $data['eventDate'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Time</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['eventTime'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Location</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['location'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Description</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['description'];?>
						    </label>
					    </div>
					</div>
		  <p class="alert alert-error">Are you sure you would like to delete this event?</p>
	    <div class="form-actions">
			<button type="submit" class="btn btn-danger">Yes</button>
			<a class="btn btn-primary" href="crud_events.php">No</a>
		</div>
	</div>
	</form>
</div>

<?php writeClosingTags(); ?>