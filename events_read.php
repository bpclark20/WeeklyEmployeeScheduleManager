<?php

#include helper php file
require 'pageWriter.php';

$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( null==$id ) {
		header("Location: crud_events.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM events where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		Database::disconnect();
	}

writeHeader("CRUD - Events - Read an event");
writeBodyOpen();

echo "<a href='crud_events.php'><h2>CRUD - Events</h2></a>";
?>

<div class="span10 offset1">
    				<div class="row">
		    			<h3>Read an Event</h3>
		    		</div>
		    		
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
					    <div class="form-actions">
						  <a class="btn btn-primary" href="crud_events.php">Back</a>
					   </div>
					
					 
					</div>
				</div>

<?php writeClosingTags(); ?>