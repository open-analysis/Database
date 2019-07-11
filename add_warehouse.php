<html>
<?php
    $errSt = "<h1 class=\"error\">";
    $errEn = "</h1>";

	$link = mysqli_connect("localhost", "root", "3ng1neering");
    $tableName = "storage";

    if(!$link){
            die("ERROR: Could not connect. " . mysqli_connect_error());
    }

	if (mysqli_select_db($link, "warehouse")){
		echo "selected warehouse database";
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (id INTEGER PRIMARY KEY, amount INTEGER)";
		if (mysqli_query($link, $sql)){
			echo "<br>created table $tableName";
			// add item
            // checks to see if item already exists, if doesn't adds a new one
            $sql = "SELECT " . $_POST['amount'] . " FROM $tableName WHERE id=" . $_POST['id'];
            if (mysqli_num_rows(mysqli_query($link,$sql)) == 0){
                $sql = "INSERT INTO $tableName (id, amount) VALUES (" . $_POST['id'] . ", " . $_POST['amount'] . ")";
                if (mysqli_query($link, $sql)){
                    echo "<br>Added: " . $_POST['amount'] . " of " . $_POST['id'] . " to $tableName";
                } else {
                    echo $errSt . "<br>didn't add values to $tableName " . mysqli_error($link) . $errEn;
                }
            } else {
                // otherwise it updates what's already there
                $prevAmount = mysqli_fetch_row(mysqli_query($link, "SELECT amount FROM $tableName WHERE id=" . $_POST['id']));
                $sql = "UPDATE $tableName SET amount=" . ((int)$_POST['amount'] + (int)$prevAmount[0])  . " WHERE id=" . $_POST['id'];
                if (mysqli_query($link, $sql)){
                    echo "<br>Updated " . $_POST['amount'] . " to " . $_POST['id'] . "<br>Total: " . ((int)$_POST['amount'] + (int)$prevAmount[0]) . " items";
                } else {
                    echo $errSt . "<br>didn't add values to $tableName " . mysqli_error($link) . $errEn;
                } 
            }
            // update item
            /*$sql = "UPDATE $tableName SET amount=600 WHERE id=" . $_POST['id'];
            if (mysqli_query($link, $sql)){
	                echo "<br>Updated: " . $_POST['id'] . " to have 6 items in $tableName";
        	} else {
                	echo "<br>didn't update values to $tableName " . mysqli_error($link);
            } 
            // delete the whole item probably unneeded unless a whole product is being discontinued.
            $sql = "DELETE FROM $tableName WHERE " . $_POST['id'];
            if (mysqli_query($link, $sql)){
	                echo "<br>Deleted: " . $_POST['id'] . " from $tableName";
        	} else {
                	echo "<br>didn't delete values to $tableName " . mysqli_error($link);
            } */
		} else {
			echo $errSt . "<br>didn't create table " . mysqli_error($link) . $errEn;
		}
	} else {
		echo $errSt . "failed to select database " . mysqli_error($link) . $errEn;
	}

    mysqli_close($link);
?>

<br>

<a href="info.php">Back</a>
</html>