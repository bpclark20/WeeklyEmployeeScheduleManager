<?php

#include helper php file
require 'pageWriter.php';

# If there is POST data, process
# the login request.
if ( !empty($_POST)) {

	// initialize $_POST variables
	$username = $_POST['username']; // username is email address
	$password = $_POST['password'];
	$passwordhash = MD5($password);
		
	// verify the username/password
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM persons WHERE email = ? AND password = ? LIMIT 1";
	$q = $pdo->prepare($sql);
	$q->execute(array($username,$passwordhash));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	
	if($data) { // if successful login set session variables
		echo "success!";
		$_SESSION['person_id'] = $data['id'];
		$sessionid = $data['id'];
		$_SESSION['person_title'] = $data['title'];
		Database::disconnect();
		header("Location: crud_assignments.php?id=$sessionid ");
		// javascript below is necessary for system to work on github
		echo "<script type='text/javascript'> document.location = 'crud_assignments.php'; </script>";
		exit();
	}
	else { // otherwise go to login error page
		Database::disconnect();
		header("Location: login_error.php");
	}
} 
// if $_POST NOT filled then display login form, below.

writeLoginHeader();
?>

<body class='text-center'>
	<form class="form-signin" action='login.php' method='post'>
		<img class='mb-4' src='img/logo.png' alt='' width='100%' height='100%'>
		<h1 class='h3 mb-3 font-weight-normal'>Please log in</h1>
		<label for='username' class='sr-only'>Email address</label>
		<input type='email' id='username' class='form-control' placeholder='Email address' required autofocus>
		<label for='password' class='sr-only'>Password</label>
		<input type='password' id='password' class='form-control' placeholder='Password' required>
		<button class='btn btn-lg btn-primary btn-block' type='submit'>Sign in</button><br>
		<a href='register.php' class='btn btn-lg btn-success btn-block' role='button'>Sign-Up</a>
		
		<p class='mt-5 mb-3 text-muted'>&copy; 2020</p>
	</form>

<?php writeClosingTags(); ?>