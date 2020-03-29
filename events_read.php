<?php

#include helper php file
require 'pageWriter.php';

if(!isset($_SESSION['employee_id'])){
	session_destroy();
	header('Location: login.php');
	exit; // exit is here just in case the header redirect fails for some reason
}
else {
	$LoggedInEmployeeID = $_SESSION['employee_id'];

	$LoggedInEmployeeTitle = getLoggedInUserTitle($LoggedInEmployeeID);
	# if the user currently logged in is not a Manager
	# or an administrator, then redirect them back to the dashboard
	if (0==strcmp($LoggedInEmployeeTitle,'Employee')) {
		header('Location: dashboard.php');
	}

	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( null==$id ) {
		header("Location: events_list.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM events where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		Database::disconnect();
	}
}



writeHeader("View Event Details");
writeBodyOpen();
?>

<h2>View Event Info</h2>

<div class="span10 offset1">

		    		
	<div class="form-horizontal" >
		<div class="control-group">
			<h3>Event</h3>
			<div class="controls">
				<label class="checkbox">
						     	<?php echo $data['description'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h3>Location</h3>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['location'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h3>Date</h3>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo dayMonthDate($data['eventDate']);?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h3>Time</h3>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo timeAmPm($data['eventTime']);?>
						    </label>
					    </div>
					  </div>
						<div class="control-group">
					    <h3>Uniform</h3>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['uniform'];?>
						    </label>
					    </div>
					  </div>
					    <div class="form-actions">
							<?php echo '<a class="btn btn-success" href="events_update.php?id='.$data['id'].'">Update</a>';?>
							<a class="btn btn-primary" href="events_list.php">Back</a>
					   </div>
					
					 
					</div>
				</div>

<?php writeClosingTags(); ?>