<?php

#include helper php file
require 'pageWriter.php';

if ( !empty($_POST)) {
		// keep track validation errors
		$fnameError = null;
		$lnameError = null;
		$emailError = null;
		$mobileError = null;
		#$passwordError = null; # Not Used Currently
		#$titleError = null; # Not Used Currently
		#$pictureError = null; # Not Used Currently
		
		# Initialize POST Variables
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		#$password = $_POST['password']; # Not Used Currently
		#$passwordhash = MD5($password); # Not Used Currently
		$title =  $_POST['title'];
		#$picture = $_POST['picture']; # Not Used Currently

		# Initialize $_FILES Variables

		
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
		$sql = "SELECT * FROM persons";
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

		# Password validation not currently implemented.
		#if (empty($password)) {
		#	$passwordError = 'Please enter valid Password';
		#	$valid = false;
		#}
		if (empty($title)) {
			$titleError = 'Please enter valid Title';
			$valid = false;
		}

		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO persons (fname, lname, email, mobile, title) values(?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($fname, $lname, $email,$mobile,$title));
			Database::disconnect();
			header("Location: crud_persons.php");
		}
	}

writeHeader("CRUD - Persons - Add a new person");
writeBodyOpen();
?>

<div class="span10 offset1">
    				<div class="row">
		    			<h3>Add a new Person</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="crud_persons_create.php" method="post">
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
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Add</button>
						  <a class="btn btn-primary" href="crud_persons.php">Back</a>
						</div>
					</form>
				</div>

<?php writeClosingTags(); ?>