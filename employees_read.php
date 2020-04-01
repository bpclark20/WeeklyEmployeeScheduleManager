<?php

#include helper php file
require 'pageWriter.php';

checkLoggedIn();

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
	header("Location: employees_list.php");
} else {
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM employees where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
}

writeHeader("View Employee Details");
writeBodyOpen();
?>

<h2>View Employee Info</h2>

<div class="span10 offset1">

		    		
	<div class="form-horizontal" >
		<div class="control-group">
			<h3>Name</h3>
			<div class="controls">
				<label class="checkbox">
						     	<?php echo $data['fname'] . " ";
						     	echo $data['lname'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h3>Email Address</h3>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['email'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h3>Phone Number</h3>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['mobile'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h3>Job-Role</h3>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['title'];?>
						    </label>
					    </div>
					  </div>
						<div class="control-group">
					    <h3>Photo</h3>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php if($data['filesize'] > 0) {
								echo '<img src="data:image/jpeg;base64,' . base64_encode($data['filecontent']) . '" width="200" height="200"/><br>';
							}?>
						    </label>
					    </div>
					  </div>
					    <div class="form-actions">
							<?php
							if ((0==strcmp($LoggedInEmployeeTitle,'Manager') and 0==strcmp($LoggedInEmployeeID,$data['id'])) or (0==strcmp($LoggedInEmployeeTitle,'Admin'))){
								echo '<a class="btn btn-success" href="employees_update.php?id='.$data['id'].'">Update</a>';
							}
							?>
							<a class="btn btn-primary" href="employees_list.php">Back</a>
					   </div>
					
					 
					</div>
				</div>

<?php writeClosingTags(); ?>