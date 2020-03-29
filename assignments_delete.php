<?php

#include helper php file
require 'pageWriter.php';

$id = 0;
	
if ( !empty($_GET['id'])) {
	$id = $_REQUEST['id'];
}

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM assignments  WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: assignments_list.php");
}
else { // otherwise, pre-populate fields to show data to be deleted

	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	# get assignment details
	$sql = "SELECT * FROM assignments where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	
	# get employee details
	$sql = "SELECT * FROM employees where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($data['assign_per_id']));
	$empdata = $q->fetch(PDO::FETCH_ASSOC);
	
	# get event details
	$sql = "SELECT * FROM events where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($data['assign_event_id']));
	$eventdata = $q->fetch(PDO::FETCH_ASSOC);
	
	Database::disconnect();
}

writeHeader("Remove Employee From Event");
writeBodyOpen();?>

<div class="span10 offset1">
		
			<div class="row">
				<h2>Remove Employee from Event</h2>
			</div>
			
			<!-- Display same information as in file: assignments_read.php -->
			
			<div class="form-horizontal">
			
				<div class="control-group">
					<h3>Employee</h3>
					<div class="controls">
						<label class="checkbox">
							<?php echo $empdata['lname'] . ', ' . $empdata['fname'] ;?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<h3>Event</h3>
					<div class="controls">
						<label class="checkbox">
							<?php echo trim($eventdata['description']) . " (" . trim($eventdata['location']) . ") ";?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<h3>Date, Time</h3>
					<div class="controls">
						<label class="checkbox">
							<?php echo dayMonthDate($eventdata['eventDate']) . ", " . timeAmPm($eventdata['eventTime']);?>
						</label>
					</div>
				</div>
			
			</div> <!-- end div: class="form-horizontal" -->

			<form class="form-horizontal" action="assignments_delete.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>"/>
				<p class="alert alert-error">Are you sure you want to remove this employee from the event?</p>
				<div class="form-actions">
					<button type="submit" class="btn btn-danger">Yes</button>
					<a class="btn btn-warning" href="assignments_list.php">No</a>
				</div>
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->

<?php writeClosingTags(); ?>
