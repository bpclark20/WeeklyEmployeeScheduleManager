<?php

#include helper php file
require 'pageWriter.php';

writeHeader("List of Employees");
writeBodyOpen();

echo "<h2>Employees</h2>";
echo "<div class='row'>
				<p>
					<a href='employees_create.php' class='btn btn-success'>Add a New Person</a>
					<a href='../index.php' class='btn btn-primary'>Back to Index</a>
				</p></div>";

writeTableOpen();

echo "<thead>
        <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>E-mail Address</th>
        <th>Phone Number</th>
        <th>Job-Role</th>
        <th>Sun. Availability</th>
        <th>Mon. Availability</th>
        <th>Tue. Availability</th>
        <th>Wed. Availability</th>
        <th>Thur. Availability</th>
        <th>Fri. Availability</th>
        <th>Sat. Availability</th>
        <th>Action</th></tr>
    </thead>";

#Connect to DB
$pdo = Database::connect();

$sql = "SELECT * FROM persons ORDER BY id ASC";

foreach ($pdo->query($sql) as $row) {
	echo "<tr>";
    echo "<td>". $row['fname'] . " " . $row['lname'] . "</td>";
    echo '<td>' . $row['email'] . '</td>';
    echo '<td>' . $row['mobile'] . '</td>';
    echo '<td>' . $row['title'] . '</td>';
    echo '<td width=250>';
   	echo '<a class="btn btn-primary" href="crud_persons_read.php?id='.$row['id'].'">Read</a>';
   	echo '&nbsp;';
   	echo '<a class="btn btn-success" href="crud_persons_update.php?id='.$row['id'].'">Update</a>';
   	echo '&nbsp;';
   	echo '<a class="btn btn-danger" href="crud_persons_delete.php?id='.$row['id'].'">Delete</a>';
   	echo '</td>';
   	echo '</tr>';
}
Database::disconnect();

echo "</table>";
echo "<br>";

writeClosingTags();