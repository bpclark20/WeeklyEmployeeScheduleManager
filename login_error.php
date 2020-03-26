<?php
# Filename: login_error.php
# Author: Brian Clark
# Purpose: This file is called by login.php if an
#          error occurs when a user tries to log in
#          to the system.
# Date Created: 2020-02-26

# include helper php file
require 'pageWriter.php';

writeHeader("Login Error");
writeBodyOpen();
?>
<div class="row">
	<h2>Login Error</h2>
</div>
		
<div class="row">
	<p>You either entered an incorrect e-mail, or password. Please try logging in again.</p>
</div>

<div class="row">
	<p><a href="login.php" class="btn btn-success">Return to Login</a></p>
</div>

<?php writeClosingTags(); ?>