<?php

#include helper php file
require 'pageWriter.php';

$id = $_GET['id'];

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

writeHeader("Read a shift assignment");
writeBodyOpen();?>


<div class="span10 offset1">
	<div class="row">
		<h2>Assignment Details</h2>
	</div>
			
	<div class="form-horizontal" >
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

		<div class="control-group">
			<h3>Uniform</h3>
			<div class="controls">
				<label class="checkbox">
					<?php echo trim($eventdata['uniform']);?>
				</label>
			</div>
		</div>
				
		<div class="form-actions">
		<?php
		if(0==strcmp($empdata['title'],'Employee')) {
			echo '<a class="btn btn-primary" href="assignments_list.php?id=' . $empdata['id']. '">Back</a>';
		}
		else {
			echo '<a class="btn btn-primary" href="assignments_list.php">Back</a>';
		}
		?>
		</div>
			
	</div> <!-- end div: class="form-horizontal" -->
			
</div> <!-- end div: class="span10 offset1" -->

<?php writeClosingTags(); ?>