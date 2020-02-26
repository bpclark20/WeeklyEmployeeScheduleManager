<?php

#include helper php file
require 'pageWriter.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if $_POST filled then process the form
	
	# same as create

	// initialize user input validation variables
	$personError = null;
	$eventError = null;
	
	// initialize $_POST variables
	$person = $_POST['person_id'];    // same as HTML name=attribute in put box
	$event = $_POST['event_id'];
	
	// validate user input
	$valid = true;
	if (empty($person)) {
		$personError = 'Please choose a volunteer';
		$valid = false;
	}
	if (empty($event)) {
		$eventError = 'Please choose an event';
		$valid = false;
	} 
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE assignments set assign_per_id = ?, assign_event_id = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($person,$event,$id));
		Database::disconnect();
		header("Location: crud_assignments.php");
	}
}
else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM assignments where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$person = $data['assign_per_id'];
	$event = $data['assign_event_id'];
	Database::disconnect();
}

writeHeader("CRUD - Assignments - Update an assignment");
writeBodyOpen();?>

<div class="span10 offset1">
	<div class="row">
		<h2>Update an Assignment</h2>
	</div>
	
	<form class="form-horizontal" action="crud_assignments_update.php?id=<?php echo $id?>" method="post">	
		<div class="control-group">
			<label class="control-label">Volunteer</label>
			<div class="controls">
				<?php
					$pdo = Database::connect();
					$sql = 'SELECT * FROM persons ORDER BY lname ASC, fname ASC';
					echo "<select class='form-control' name='person_id' id='person_id'>";
					foreach ($pdo->query($sql) as $row) {
						if($row['id']==$person)
							echo "<option selected value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
						else
							echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
					}
					echo "</select>";
					Database::disconnect();
				?>
			</div>	<!-- end div: class="controls" -->
		</div> <!-- end div class="control-group" -->
			  
		<div class="control-group">
			<label class="control-label">Event</label>
			<div class="controls">
				<?php
					$pdo = Database::connect();
					$sql = 'SELECT * FROM events ORDER BY eventDate ASC, eventTime ASC';
					echo "<select class='form-control' name='event_id' id='event_id'>";
					foreach ($pdo->query($sql) as $row) {
						if($row['id']==$event) {
							echo "<option selected value='" . $row['id'] . " '> " . dayMonthDate($row['eventDate']) . " (" . timeAmPm($row['eventTime']) . ") - " . trim($row['description']) . " (" . trim($row['location']) . ") " . "</option>";
						}
						else {
							echo "<option value='" . $row['id'] . " '> " . dayMonthDate($row['eventDate']) . " (" . timeAmPm($row['eventTime']) . ") - " . trim($row['description']) . " (" . trim($row['location']) . ") " . "</option>";
						}
					}
					echo "</select>";
					Database::disconnect();
				?>
			</div>	<!-- end div: class="controls" -->
		</div> <!-- end div class="control-group" -->

		<div class="form-actions">
			<button type="submit" class="btn btn-success">Update</button>
				<a class="btn btn-warning" href="fr_assignments.php">Back</a>
		</div>	
	</form>			
</div> <!-- end div: class="span10 offset1" -->

<?php writeClosingTags(); ?>