<?php

#include helper php file
require 'pageWriter.php';


writeHeader();
writeBodyOpen();

echo "<a href='display_list.php'><h2>Display List of Customers</h2></a>";
echo "<a href='create_record.php'><p>Create Record</p></a>";

writeTableOpen();

echo "<thead>
        <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Phone Number</th></tr>
    </thead>";

#Connect to DB
$pdo = Database::connect();

$sql = "SELECT * FROM customers ORDER BY id ASC";

foreach ($pdo->query($sql) as $row) {
	echo "<tr>";
    echo "<td>". $row['id'] . "</td>";
    echo '<td>' . $row['fname'] . '</td>';
    echo '<td>' . $row['lname'] . '</td>';
    echo '<td>' . $row['phone'] . '</td>';
    echo "</td></tr>";
}
Database::disconnect();

echo "</table>";
echo "<br>";

writeClosingTags();
