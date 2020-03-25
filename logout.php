<?php
# include helper php file
require 'pageWriter.php';
# destroy the session for the currently logged in user
session_destroy(); 
# redirect back to the login page
header("Location: login.php");
?>