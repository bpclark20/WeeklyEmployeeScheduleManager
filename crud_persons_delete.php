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
	$sql = "DELETE FROM persons  WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: crud_persons.php");
}
else { //Otherwise prepopulate the date fields and bring up the details of the selected person to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	# Get details of person
	$sql = "SELECT * FROM persons where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);

	Database::disconnect();
}

writeHeader("CRUD - Persons - Delete a person");
writeBodyOpen();
?>
<div class="span10 offset1">
	<div class="row">
		<h2>Delete a Person</h2>
	</div>
		    		
	<form class="form-horizontal" action="crud_persons_delete.php" method="post">
		<input type="hidden" name="id" value="<?php echo $id;?>"/>
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

		  <p class="alert alert-error">Are you sure you want to delete this person?</p>
	    <div class="form-actions">
			<button type="submit" class="btn btn-danger">Yes</button>
			<a class="btn btn-primary" href="crud_persons.php">No</a>
		</div>
	</div>
	</form>
</div>

<?php writeClosingTags(); ?>