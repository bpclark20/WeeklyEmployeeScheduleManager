<?php

#include helper php file
require 'pageWriter.php';

checkLoggedIn();

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
	$sql = "DELETE FROM employees WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: employees_list.php");
}
else { //Otherwise prepopulate the date fields and bring up the details of the selected person to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	# Get details of person
	$sql = "SELECT * FROM employees where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);

	$fileSize = $data['filesize'];
	$picture = $data['filecontent'];

	Database::disconnect();
}

writeHeader("Delete an Employee");
writeBodyOpen();
?>
<div class="span10 offset1">
	<div class="row">
		<h2>Delete an Employee</h2>
	</div>
		    		
	<form class="form-horizontal" action="employees_delete.php" method="post">
		<input type="hidden" name="id" value="<?php echo $id;?>"/>
<div class="form-horizontal">
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
					<?php if($fileSize > 0) {
						echo '<div class="control-group">';
						echo '<h4>Photo</h4>';
						echo '<div class="controls">';
						echo '<img src="data:image/jpeg;base64,' . base64_encode($picture) . '" width="200" height="200"/><br>';
						echo '</div>';
						echo '</div>';
					}?>


						

		  <p class="alert alert-error">Are you sure you want to delete this person?</p>
	    <div class="form-actions">
			<button type="submit" class="btn btn-danger">Yes</button>
			<a class="btn btn-primary" href="employees_list.php">No</a>
		</div>
	</div>
	</form>
</div>

<?php writeClosingTags(); ?>