<?php

#include helper php file
require 'pageWriter.php';

writeHeader("CRUD - Assignments");
writeBodyOpen();

echo "<h2>CRUD - Assignments</h2>";
echo "<div class='row'>
				<p>
					<a href='crud_assignments_create.php' class='btn btn-success'>Create New Assignment</a>
          <a href='../index.php' class='btn btn-primary'>Back to Index</a>
				</p></div>";

writeTableOpen();

echo "<thead>
        <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Event</th>
        <th>Volunteer</th>
        <th>Action</th></tr>
    </thead>";

#Connect to DB
$pdo = Database::connect();

$sql = "SELECT events.eventDate, events.eventTime, events.location, events.description, assignments.id, persons.fname, persons.lname FROM events, assignments, persons WHERE persons.id = assignments.assign_per_id AND events.id = assignments.assign_event_id ORDER BY events.eventDate ASC";

foreach ($pdo->query($sql) as $row) {
	echo "<tr>";
    echo "<td>". $row['eventDate'] . "</td>";
    echo '<td>' . $row['eventTime'] . '</td>';
    echo '<td>' . $row['location'] . '</td>';
    echo '<td>' . $row['description'] . '</td>';
    echo '<td>' . $row['fname'] . " " . $row['lname'] . '</td>';
    echo '<td width=250>';
   	echo '<a class="btn btn-primary" href="crud_assignments_read.php?id='.$row['id'].'">Read</a>';
   	echo '&nbsp;';
   	echo '<a class="btn btn-success" href="crud_assignments_update.php?id='.$row['id'].'">Update</a>';
   	echo '&nbsp;';
   	echo '<a class="btn btn-danger" href="crud_assignments_delete.php?id='.$row['id'].'">Delete</a>';
   	echo '</td>';
   	echo '</tr>';
}
Database::disconnect();

echo "</table>";
echo "<br>";

writeClosingTags();