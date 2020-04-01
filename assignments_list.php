<?php

#include helper php file
require 'pageWriter.php';


checkLoggedIn();

$LoggedInID = $_SESSION['employee_id'];

if (isset($_GET['id'])) {
  $id = $_GET['id'];
}
else {
  $id = null;
}



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

if ($id == null and (0==strcmp($LoggedInTitle,'Employee'))) {
  header('Location: dashboard.php');
}

# Grab all of the events
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM assignments";
$q = $pdo->prepare($sql);
$q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();

writeHeader("View All Assigned Shifts");
writeBodyOpen();
?>

<h1>View All Assigned Shifts</h1>

<div class="row">
  <p><strong>Currently Logged In As: </strong> <?php echo $employeeFirstName . " " . $employeeLastName; ?></p>
</div>
<div class="row">
<?php if (0!=strcmp($LoggedInTitle,'Employee')) {
  echo "<a href='assignments_create.php' class='btn btn-success'>Schedule a Shift</a>&nbsp;";
}
?>
  <a href='dashboard.php' class='btn btn-primary'>Back to Dashboard</a>&nbsp;
  <a class="btn btn-danger" href="logout.php">Logout</a>&nbsp;
</div>

<div class="row">
  <?php writeTableOpen(); ?>
  <thead>
    <tr>
      <th>Event</th>
      <th>Location</th>
      <th>Date</th>
      <th>Time</th>
      <th>Assigned Employee</th>
      <th>Actions</th></tr>
  </thead>
  <?php 
    $pdo = Database::connect();

    if($id != null) {
			$sql = "SELECT * FROM assignments 
			LEFT JOIN employees ON employees.id = assignments.assign_per_id 
			LEFT JOIN events ON events.id = assignments.assign_event_id
			WHERE employees.id = $id 
      ORDER BY eventDate ASC, eventTime ASC, lname ASC, fname ASC";
    }
    else { 
      $sql = "SELECT * FROM assignments
      LEFT JOIN employees ON employees.id = assignments.assign_per_id
      LEFT JOIN events ON events.id = assignments.assign_event_id
      ORDER BY eventDate ASC, eventTime ASC, lname ASC, fname ASC";
      }



    foreach($pdo->query($sql) as $row) {
      echo "<tr>";
      echo "<td>". $row['description'] . "</td>";
      echo '<td>' . $row['location'] . '</td>';
      echo '<td>' . dayMonthDate($row['eventDate']) . '</td>';
      echo '<td>' . timeAmPm($row['eventTime']) . '</td>';
      echo '<td>' . $row['fname'] . " " . $row['lname'] . '</td>';
      echo '<td width=250>';
   	  echo '<a class="btn btn-primary" href="assignments_read.php?id='.$row['id'].'">Read</a>';
      echo '&nbsp;';
      if(0!=strcmp($LoggedInTitle,'Employee')) {
        echo '<a class="btn btn-success" href="assignments_update.php?id='.$row['id'].'">Update</a>';
        echo '&nbsp;';
        echo '<a class="btn btn-danger" href="assignments_delete.php?id='.$row['id'].'">Delete</a>';
        echo '</td>';
      }
   	  echo '</tr>';
    }
  ?>

</div>