<?php

#include helper php file
require 'pageWriter.php';


writeHeader();
writeBodyOpen();

echo "<a href='display_list.php'><h2>Display List of Customers</h2></a>";
?>

<form method='post' action='insert_record.php'>


	First Name: <input name='fname' type='text' /><br>
	Last Name: <input name='lname' type='text' /><br>
	Phone Number: <input name='phone' type='text' /><br>
    <button type='submit' class="btn btn-primary">Submit</button>
    <button type="button" class="btn btn-warning" onclick="window.history.back();">Cancel</button>
    
    
</form>
<?php writeClosingTags(); ?>