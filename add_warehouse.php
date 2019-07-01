<?php
	$link = mysqli_connect("localhost", "root", "3ng1neering");

        if(!$link){
                die("ERROR: Could not connect. " . mysqli_connect_error());
        }

	if (mysqli_select_db($link, "warehouse")){
		echo "selected warehouse database";
		$sql = "CREATE TABLE IF NOT EXISTS storage (id INTEGER, amount INTEGER)";
		if (mysqli_query($link, $sql)){
			echo "<br>created table storage";
			// add item
			$sql = "INSERT INTO storage (id, amount) VALUES (" . $_POST['id'] . ", " . $_POST['amount'] . ")";
            if (mysqli_query($link, $sql)){
	                echo "<br>Added: " . $_POST['amount'] . " of " . $_POST['id'] . " to storage";
        	} else {
                	echo "<br>didn't add values to storage " . mysqli_error($link);
            }
            // update item
            $sql = "UPDATE storage SET amount=600 WHERE id=" . $_POST['id'];
            if (mysqli_query($link, $sql)){
	                echo "<br>Updated: " . $_POST['id'] . " to have 6 items in storage";
        	} else {
                	echo "<br>didn't update values to storage " . mysqli_error($link);
            }
            // delete the whole item probably unneeded unless a whole product is being discontinued.
            $sql = "DELETE FROM storage WHERE " . $_POST['id'];
            if (mysqli_query($link, $sql)){
	                echo "<br>Deleted: " . $_POST['id'] . " from storage";
        	} else {
                	echo "<br>didn't delete values to storage " . mysqli_error($link);
            }
		} else {
			echo "<br>didn't create table " . mysqli_error($link);
		}
	} else {
		echo "failed to select database " . mysqli_error($link);
	}
?>
