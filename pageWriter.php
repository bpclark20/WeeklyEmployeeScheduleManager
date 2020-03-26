<?php

/* 
 * This file contains functions that I re-used all over the place.
 */

// Ensures all files have the capability to connect to the database
require '../db/database.php';

// Ensures all pages maintain session state
session_start();

# Checks if a user is logged in, if so
# true is returned, if not false is returned

function checkLoggedIn() {
  if(!isset($_SESSION['employee_id'])){
    return true;
  }
  else{
    return false;
  }
}

// Checks the SQL connection
function checkConnect($mysqli) {
  if ($mysqli->connect_errno) {
      die('Unable to connect to database [' . $mysqli->connect_error . ']');
      exit();
  }
}


function dayMonthDate($dateval) {
    // receives $dateval in format: 2017-03-13
    // returns $dateval in format: Mon Mar-13
    // see: https://www.w3schools.com/php/func_date_date.asp
    $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    $day = $days[date('w', strtotime($dateval))];
    $dateval = $day . ' ' . date('M-d', strtotime($dateval));
    return $dateval;
}

function timeAmPm($timeval) {
  // receives $timeval in format: 00:00:00
  // returns $timeval in format: 00:00 am, or 00:00 pm
  if ($timeval < 12) // morning (am)
    $timeval = substr($timeval, 0, 5) . ' am';
  else { // noon-afternoon-evening (pm)
    $hour = substr($timeval,0,2);
    $hour = $hour - 12;
    if ($hour == 0) $hour = 12;
    if ($hour < 10) $hour = '0' . $hour;
    $timeval = $hour . substr($timeval,2,3) . ' pm';
  }
  return $timeval;
} 

/* This function writes the opening HTML tags and includes
 * bootstrap for a consistent UI
 */
function writeHeader($strPageTitle) {
    echo "<!DOCTYPE html><html lang='en'>
        <head><meta charset='UTF-8'>
        <title>" . $strPageTitle. "</title>
        <!-- Latest compiled and minified CSS -->
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' integrity='sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh' crossorigin='anonymous'>
        </head>";
}

function writeLoginHeader() {
    echo "<!DOCTYPE html><html lang='en'>
        <head><meta charset='UTF-8'>
        <title>Apple Mountain Employee Scheduler - Login</title>
        <!-- Latest compiled and minified CSS -->
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' integrity='sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh' crossorigin='anonymous'>
        <link rel='stylesheet' href='css/signin.css'>
        </head>";
}

// This function writes the opening table tags used when presenting DB info.
function writeTableOpen() {
    echo "<table class='table table-striped table-bordered table-responsive'>";
}

function writeClosingTags() {
    echo"</div></body></html>";
}

function writeBodyOpen() {
    echo "<body>";
    echo "<div class='container'>";
}

function getLoggedInUserTitle($currentlyLoggedInUser) {
  $pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM employees where id = ? LIMIT 1";
	$q = $pdo->prepare($sql);
	$q->execute(array($currentlyLoggedInUser));
  $data = $q->fetch(PDO::FETCH_ASSOC);
  return $data['title'];
}