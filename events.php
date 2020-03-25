<?php

#include helper php file
require 'pageWriter.php';

writeHeader("CRUD - Events");
writeBodyOpen();

echo "<h2>CRUD - Events</h2>";
echo "<div class='row'>
				<p>
					<a href='crud_events_create.php' class='btn btn-success'>Create New Event</a>
          <a href='../index.php' class='btn btn-primary'>Back to Index</a>
				</p></div>";

writeTableOpen();

echo "<thead>
        <tr>
        <th>Event Date</th>
        <th>Event Time</th>
        <th>Event Location</th>
        <th>Event Description</th>
        <th>Actions</th></tr>
    </thead>";

#Connect to DB
$pdo = Database::connect();

$sql = "SELECT * FROM events ORDER BY id ASC";

foreach ($pdo->query($sql) as $row) {
	echo "<tr>";
    echo "<td>". $row['eventDate'] . "</td>";
    echo '<td>' . $row['eventTime'] . '</td>';
    echo '<td>' . $row['location'] . '</td>';
    echo '<td>' . $row['description'] . '</td>';
    echo '<td width=250>';
   	echo '<a class="btn btn-primary" href="crud_events_read.php?id='.$row['id'].'">Read</a>';
   	echo '&nbsp;';
   	echo '<a class="btn btn-success" href="crud_events_update.php?id='.$row['id'].'">Update</a>';
   	echo '&nbsp;';
   	echo '<a class="btn btn-danger" href="crud_events_delete.php?id='.$row['id'].'">Delete</a>';
   	echo '</td>';
   	echo '</tr>';
}
Database::disconnect();

echo "</table>";
echo "<br>";

writeClosingTags();