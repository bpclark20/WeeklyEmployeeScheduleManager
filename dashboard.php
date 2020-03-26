<?php

#include helper php file
require 'pageWriter.php';

# If employee_id is not set, the user
# is not logged in, so redirect them
# to the login page.
if(!isset($_SESSION['employee_id'])){
    session_destroy();
    header('Location: login.php');
    exit; // exit is here just in case the header redirect fails for some reason
}
else {
    $LoggedInID = $_SESSION['employee_id'];
    
    # Grab the extra details needed about this employee
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM employees WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($LoggedInID));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    

    $employeeFirstName = $data['fname'];
    $employeeLastName = $data['lname'];
		$employeeEmail = $data['email'];
		$employeeTitle = $data['title'];
    Database::disconnect();
}


writeHeader("Employee Dashboard");
writeBodyOpen();
?>

<h1>Apple Mountain Employee Dashboard</h1>
<div class="span10 offset1">
	    			<div class="form-horizontal" >
					  <div class="control-group">
					    <h4>Name</h4>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php echo $employeeFirstName . " ";
						     	echo $employeeLastName;?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Email Address</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $employeeEmail;?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <h4>Job Role</h4>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $employeeTitle;?>
						    </label>
					    </div>
					  </div>
					    <div class="form-actions">
							<a class="btn btn-success" href="https://github.com/diablodelnoche/cis355">View Github Code</a>
							<a class="btn btn-danger" href="logout.php">Logout</a>&nbsp;
              <a class="btn btn-primary" href="employees_update.php?id=<?php echo $LoggedInID . '"'; ?>>Update My Info</a>&nbsp;
							<?php 
								if(0==strcmp($employeeTitle,"Admin")) {
									echo "<a class='btn btn-primary' href='employees_list.php'>View Employees</a>&nbsp;";
									echo "<a class='btn btn-primary' href='events_list.php'>View Events</a>&nbsp;";
									echo "<a class='btn btn-primary' href='assignments_list.php'>View Schedule</a>&nbsp;";
								}
								if(0==strcmp($employeeTitle,"Manager")) {
									echo "<a class='btn btn-primary' href='employees_list.php'>View Employees</a>&nbsp;";
									echo "<a class='btn btn-primary' href='events_list.php'>View Events</a>&nbsp;";
									echo "<a class='btn btn-primary' href='assignments_list.php'>View Schedule</a>&nbsp;";
								}
						  		if(0==strcmp($employeeTitle,"Employee")) {
									echo "<a class='btn btn-primary' href='assignments_list.php'>My Scheduled Shifts</a>&nbsp;";
									echo "<a class='btn btn-primary' href='availability_update.php'>My Availability</a>&nbsp;";
						  	} ?>
					   </div>
					
					 
					</div>
				</div>