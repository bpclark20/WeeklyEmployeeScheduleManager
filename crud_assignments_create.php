<?php

#include helper php file
require 'pageWriter.php';

if ( !empty($_POST)) {

	// initialize user input validation variables
	$personError = null;
	$eventError = null;
	
	// initialize $_POST variables
	$person = $_POST['person'];    // same as HTML name= attribute in put box
	$event = $_POST['event'];
	
	// validate user input
	$valid = true;
	if (empty($person)) {
		$personError = 'Please choose a person';
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
		$sql = "INSERT INTO assignments 
			(assign_per_id,assign_event_id) 
			values(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($person,$event));
		Database::disconnect();
		header("Location: crud_assignments.php");
	}
}

writeHeader("CRUD - Assignments - Create a New Assignment");
writeBodyOpen();
?>

<div class="span10 offset1">
			<div class="row">
				<h3>Assign a Volunteer to an Event</h3>
			</div>
	
			<form class="form-horizontal" action="crud_assignments_create.php" method="post">
		
				<div class="control-group">
					<label class="control-label">Volunteer</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM persons ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='person' id='person_id'>";
							if($eventid) // if $_GET exists restrict person options to logged in user
								foreach ($pdo->query($sql) as $row) {
									if($personid==$row['id'])
										echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
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
							echo "<select class='form-control' name='event' id='event_id'>";
								foreach ($pdo->query($sql) as $row) {
									echo "<option value='" . $row['id'] . " '> " . dayMonthDate($row['eventDate']) . " (" . timeAmPm($row['eventTime']) . ") - " .
									trim($row['description']) . " (" . 
									trim($row['location']) . ") " .
									"</option>";
								}
								
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
						<a class="btn btn-primary" href="crud_assignments.php">Back</a>
				</div>
				
			</form>
<?php writeClosingTags(); ?> 