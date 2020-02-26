<?php

#include helper php file
require 'pageWriter.php';

$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( null==$id ) {
		header("Location: crud_persons.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM persons where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		Database::disconnect();
	}

writeHeader("CRUD - Persons - Read a person");
writeBodyOpen();

echo "<h2>CRUD - Persons</h2>";

?>

<div class="span10 offset1">
    				<div class="row">
		    			<h3>Person Details</h3>
		    		</div>
		    		
	    			<div class="form-horizontal" >
					  <div class="control-group">
					    <h4>Name</h4>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php echo $data['fname'] . " ";
						     	echo $data['lname'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Email Address</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['email'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Phone Number</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['mobile'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Title</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['title'];?>
						    </label>
					    </div>
					  </div>
					  <!-- photo upload not currently implemented
					  <div class="control-group">
					    <h4>Photo</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['filecontent'];?>
						    </label>
					    </div>
					  </div>
					-->
					    <div class="form-actions">
						  <a class="btn btn-primary" href="crud_persons.php">Back</a>
					   </div>
					
					 
					</div>
				</div>

<?php writeClosingTags(); ?>