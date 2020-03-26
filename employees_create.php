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
}

if ( !empty($_POST)) {
		// keep track validation errors
		$fnameError = null;
		$lnameError = null;
		$emailError = null;
		$mobileError = null;
		$titleError = null; 
		$pictureError = null;
		$passwordError = null; 
		
		# Initialize POST Variables
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		$title =  $_POST['title'];
		$password = $_POST['password']; 
		$passwordhash = MD5($password); 
		$title =  $_POST['title'];
		#$picture = $_POST['picture']; # Not Used Currently

		# Initialize $_FILES Variables
		if (empty($_FILES)) {
			$fileSize = 0;
		}
		else {
			$fileName = $_FILES['picture']['name'];
			$tmpName  = $_FILES['picture']['tmp_name'];
			$fileSize = $_FILES['picture']['size'];
			$fileType = $_FILES['picture']['type'];
			$content = file_get_contents($tmpName);
		}

		
		# Validate User Input
		$valid = true;
		if (empty($fname)) {
			$fnameError = 'Please enter First Name';
			$valid = false;
		}

		if (empty($lname)) {
			$lnameError = 'Please enter Last Name';
			$valid = false;
		}
		
		# Ensure no 2 records contain identical email addresses
		if (empty($email)) {
			$emailError = 'Please enter Email Address';
			$valid = false;
		} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$emailError = 'Please enter a valid Email Address';
			$valid = false;
		}

		$pdo = Database::connect();
		$sql = "SELECT * FROM employees";
		foreach($pdo->query($sql) as $row) {

			if($email == $row['email']) {
				$emailError = 'Email has already been registered!';
				$valid = false;
			}
		}
		Database::disconnect();
		
		if (empty($mobile)) {
			$mobileError = 'Please enter a mobile number.';
			$valid = false;
		}

		# Ensure Phone Number is entered in the following format
		# 000-000-0000
		if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $mobile)) {
		$mobileError = 'Please write phone number in form 000-000-0000';
		$valid = false;
		}

		if (empty($password)) {
			$passwordError = 'Please enter valid Password';
			$valid = false;
		}
		if (empty($title)) {
			$titleError = 'Please enter valid Title';
			$valid = false;
		}

		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO employees fname = ?, lname = ?, email = ?, mobile = ?, password=?, title = ?, filename = ?,filesize = ?,filetype = ?,filecontent = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($fname, $lname, $email,$mobile,$passwordhash, $title, $fileName, $fileSize, $fileType, $content));
			Database::disconnect();
			header("Location: employees_list.php");
		}
	}

writeHeader("Add a new employee");
writeBodyOpen();
?>

<div class="span10 offset1">
	<div class="row">
		<h2>Add a new Employee</h2>
	</div>
    		
	<form class="form-horizontal" action="employees_create.php" method="post" enctype="multipart/form-data">
					  <div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
					    <label class="control-label">First Name</label>
					    <div class="controls">
					      	<input name="fname" type="text"  placeholder="First Name">
					      	<?php if (!empty($fnameError)): ?>
					      		<span class="help-inline"><?php echo $fnameError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($lnameError)?'error':'';?>">
					    <label class="control-label">Last Name</label>
					    <div class="controls">
					      	<input name="lname" type="text"  placeholder="Last Name">
					      	<?php if (!empty($lnameError)): ?>
					      		<span class="help-inline"><?php echo $lnameError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					    <label class="control-label">Email Address</label>
					    <div class="controls">
					      	<input name="email" type="text" placeholder="Email Address" required>
					      	<?php if (!empty($emailError)): ?>
					      		<span class="help-inline"><?php echo $emailError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
						<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					    <label class="control-label">Password</label>
					    <div class="controls">
					      	<input name="password" type="password" placeholder="Password" required>
					      	<?php if (!empty($passwordError)): ?>
					      		<span class="help-inline"><?php echo $passwordError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
					    <label class="control-label">Phone Number</label>
					    <div class="controls">
					      	<input name="mobile" type="text"  placeholder="Phone Number">
					      	<?php if (!empty($mobileError)): ?>
					      		<span class="help-inline"><?php echo $mobileError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($titleError)?'error':'';?>">
					    <label class="control-label">Title</label>
					    <div class="controls">
					      	<input name="title" type="text"  placeholder="Title">
					      	<?php if (!empty($titleError)): ?>
					      		<span class="help-inline"><?php echo $titleError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($pictureError)?'error':'';?>">
					    <label class="control-label">Photo</label>
					    <div class="controls">
							<label for="picture">Select a photo:</label>
					      	<input name="picture" id="picture" type="file" required>
					      	<?php if (!empty($pictureError)): ?>
					      		<span class="help-inline"><?php echo $pictureError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Add Employee</button>
						  <a class="btn btn-warning" href="dashboard.php">Back</a>
						</div>
					</form>
				</div>

<?php writeClosingTags(); ?>