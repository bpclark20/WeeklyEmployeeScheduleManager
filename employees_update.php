<?php

#include helper php file
require 'pageWriter.php';

$id = null;
if(!isset($_SESSION['employee_id'])){
    session_destroy();
    header('Location: login.php');
    exit; // exit is here just in case the header redirect fails for some reason
}
else{
	$id = $_SESSION['employee_id'];
	if ( !empty($_POST)) {
		// keep track validation errors
		$fnameError = null;
		$lnameError = null;
		$emailError = null;
		$mobileError = null;
		$titleError = null; 
		$pictureError = null; 
		
		# Initialize POST Variables
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		$title =  $_POST['title'];
		//$picture = $_POST['picture'];

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

		# Initalize $_FILES variables
		
		
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

		if (empty($title)) {
			$titleError = 'Please enter valid Title';
			$valid = false;
		}
		
		// update data
		if ($valid) {
			if($fileSize > 0) {# if file size is greater than 0, the photo was updated
				$pdo = Database::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "UPDATE employees  set fname = ?, lname = ?, email = ?, mobile = ?, title = ?, filename = ?,filesize = ?,filetype = ?,filecontent = ? WHERE id = ?";
				$q = $pdo->prepare($sql);
				$q->execute(array($fname,$lname,$email,$mobile,$title,$fileName,$fileSize,$fileType,$content,$id));
				Database::disconnect();
				header("Location: dashboard.php");
			}
			else { #otherwise update all the fields besides file fields
				$pdo = Database::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "UPDATE employees set fname = ?, lname = ?, email = ?, mobile = ?, title = ? WHERE id = ?";
				$q = $pdo->prepare($sql);
				$q->execute(array($fname,$lname,$email,$mobile,$title,$id));
				Database::disconnect();
				header("Location: dashboard.php");
			}
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM employees where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$fname = $data['fname'];
		$lname = $data['lname'];
		$email = $data['email'];
		$mobile = $data['mobile'];
		$title = $data['title'];
		$fileName = $data['filename'];
		$fileSize = $data['filesize'];
		$fileType = $data['filetype'];
		$picture = $data['filecontent'];
		Database::disconnect();
	}
}
writeHeader("Update my Info");
writeBodyOpen();
?>

<div class="span10 offset1">
    				<div class="row">
		    			<h2>Update my Info</h2>
		    		</div>
    		
	    			<form class="form-horizontal" action="employees_update.php" method="post" enctype="multipart/form-data">
					  <div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
					    <label class="control-label">First Name</label>
					    <div class="controls">
					      	<input name="fname" type="text"  placeholder="First Name" value="<?php echo !empty($fname)?$fname:'';?>">
					      	<?php if (!empty($fnameError)): ?>
					      		<span class="help-inline"><?php echo $fnameError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($lnameError)?'error':'';?>">
					    <label class="control-label">Last Name</label>
					    <div class="controls">
					      	<input name="lname" type="text"  placeholder="Last Name" value="<?php echo !empty($lname)?$lname:'';?>">
					      	<?php if (!empty($lnameError)): ?>
					      		<span class="help-inline"><?php echo $lnameError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					    <label class="control-label">Email Address</label>
					    <div class="controls">
					      	<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
					      	<?php if (!empty($emailError)): ?>
					      		<span class="help-inline"><?php echo $emailError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
					    <label class="control-label">Phone Number</label>
					    <div class="controls">
					      	<input name="mobile" type="text"  placeholder="Phone Number" value="<?php echo !empty($mobile)?$mobile:'';?>">
					      	<?php if (!empty($mobileError)): ?>
					      		<span class="help-inline"><?php echo $mobileError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($titleError)?'error':'';?>">
					    <label class="control-label">Title</label>
					    <div class="controls">
					      	<input name="title" type="text"  placeholder="Title" value="<?php echo !empty($title)?$title:'';?>">
					      	<?php if (!empty($titleError)): ?>
					      		<span class="help-inline"><?php echo $titleError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($pictureError)?'error':'';?>">
					    <label class="control-label">Photo</label>
					    <div class="controls">
							<?php
							if($fileSize > 0) {
								echo '<img src="data:image/jpeg;base64,' . base64_encode($picture) . '" width="200" height="200"/><br>';
							}
							?>
							<label for="picture">Select a photo:</label>
					      	<input name="picture" id="picture" type="file" required>
					      	<?php if (!empty($pictureError)): ?>
					      		<span class="help-inline"><?php echo $pictureError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Update</button>
						  <a class="btn btn-warning" href="dashboard.php">Back</a>
						</div>
					</form>
				</div>
<?php writeClosingTags(); ?>