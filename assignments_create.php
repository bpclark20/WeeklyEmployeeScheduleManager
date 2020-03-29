<?php

#include helper php file
require 'pageWriter.php';

if ( !empty($_POST)) {
	// keep track validation errors
	$employeeError = null;
	$eventError = null;

	
	// keep track post values
	$employee = $_POST['employee'];
	$event = $_POST['event'];

	
	// validate input
	$valid = true;
	if (empty($employee)) {
		$dateError = 'Please select an employee.';
		$valid = false;
	}
	
	if (empty($event)) {
		$timeError = 'Please select an event.';
		$valid = false;
	} 

	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO assignments (assign_per_id,assign_event_id) values(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($employee, $event));
		Database::disconnect();
		header("Location: assignments_list.php");
	}
}	

writeHeader("Schedule a Shift");
writeBodyOpen();
?>

<div class="span10 offset1">
    				<div class="row">
		    			<h3>Add a new Event</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="assignments_create.php" method="post">
					  <div class="control-group <?php echo !empty($employeeError)?'error':'';?>">
					    <label class="control-label">Employee</label>
					    <div class="controls">
							<?php 
							$pdo = Database::connect();
							$sql = 'SELECT * FROM employees ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='employee' id='employee_id'>";
							foreach ($pdo->query($sql) as $row) {
								if (0 == strcmp($row['title'], 'Employee')) {#only assign employees to events, not managers/admins
									echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								}
								
							}
							echo "</select>";
							Database::disconnect();
							?>
							</div>
							</div>

					  <div class="control-group <?php echo !empty($eventError)?'error':'';?>">
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
					    </div>
					  </div>
					  
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Assign Employee to Event</button>
						  <a class="btn btn-primary" href="assignments_list.php">Back</a>
						</div>
					</form>
				</div>

<?php writeClosingTags(); ?>