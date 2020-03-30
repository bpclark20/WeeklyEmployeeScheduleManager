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


   # Grab the extra details needed about the currently logged in
   # employee
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM employees WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($LoggedInID));
    $data = $q->fetch(PDO::FETCH_ASSOC);

  $employeeFirstName = $data['fname'];
  $employeeLastName = $data['lname'];
  $employeeEmail = $data['email'];
  $LoggedInTitle = $data['title'];
  Database::disconnect();

  if(0==strcmp($LoggedInTitle,'Employee')) {
    header('Location: dashboard.php');
  }

  # Grab all of the employees
  $pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM employees";
	$q = $pdo->prepare($sql);
	$q->execute(array($LoggedInID));
  $data = $q->fetch(PDO::FETCH_ASSOC);
  Database::disconnect();
}

writeHeader("View All Employees");
writeBodyOpen();
?>

<h1>View All Employees</h1>

<div class="row">
  <p><strong>Currently Logged In As: </strong> <?php echo $employeeFirstName . " " . $employeeLastName; ?></p>
</div>
<div class="row">
  <a href='employees_create.php' class='btn btn-success'>Add a New Employee</a>&nbsp;
  <a href='dashboard.php' class='btn btn-primary'>Back to Dashboard</a>&nbsp;
  <a class="btn btn-danger" href="logout.php">Logout</a>&nbsp;
</div>

<div class="row">
  <?php writeTableOpen(); ?>
  <thead>
    <tr>
      <th>Name</th>
      <th>E-mail Address</th>
      <th>Phone Number</th>
      <th>Title</th>
      <th>Action</th></tr>
  </thead>
  <?php 
    $pdo = Database::connect();

    $sql = "SELECT * FROM employees ORDER BY id ASC";

    foreach($pdo->query($sql) as $row) {
      echo "<tr>";
      echo "<td>". $row['fname'] . " " . $row['lname'] . "</td>";
      echo '<td>' . $row['email'] . '</td>';
      echo '<td>' . $row['mobile'] . '</td>';
      echo '<td>' . $row['title'] . '</td>';
      echo '<td width=250>';
   	  echo '<a class="btn btn-primary" href="employees_read.php?id='.$row['id'].'">Read</a>';
      echo '&nbsp;';
      echo '<a class="btn btn-success" href="employees_update.php?id='.$row['id'].'">Update</a>';
      echo '&nbsp;';
      if (0!=strcmp($LoggedInID,$row['id'])) {
        echo '<a class="btn btn-danger" href="employees_delete.php?id='.$row['id'].'">Delete</a>';
      }
   	  echo '</td>';
   	  echo '</tr>';
    }
  ?>

</div>