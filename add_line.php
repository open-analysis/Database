<html>
<?php
	$link = mysqli_connect("localhost", "root", "3ng1neering");
    $tableName = "line";

        if(!$link){
                die("ERROR: Could not connect. " . mysqli_connect_error());
        }

	if (mysqli_select_db($link, "warehouse")){
		echo "selected warehouse database";
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (id INTEGER, amount INTEGER, max INTEGER DEFAULT 200)";
		if (mysqli_query($link, $sql)){
			echo "<br>created table $tableName";
			// add item (takes them from storage & puts them into the line)
            // checks to see if there's enough in storage
			$sql = "SELECT amount FROM storage WHERE id = " . $_POST['id'];
            if ($result = (int)mysqli_fetch_row(mysqli_query($link, $sql))[0]) {
                if ($result >= (int)$_POST['amount']){
                    echo "<br>There's enough products in storage";
                    // removes the amount indicated from the storage
                    $sql = "UPDATE storage SET amount = " . ($result - (int)$_POST['amount']) . " WHERE id = " . $_POST['id'];
                    if (mysqli_query($link, $sql)){
                        // adds said amount to the line table
                        $sql = "INSERT INTO $tableName (id, amount) VALUES (" . $_POST['id'] . ", " . $_POST['amount'] . ")";
                        if (mysqli_query($link, $sql)){
                            echo "<br>Added: " . $_POST['amount'] . " of " . $_POST['id'] . " to $tableName";
                        } else {
                            echo "<br>didn't add values to $tableName " . mysqli_error($link);
                        }
                    } else {
                        echo "<br>couldn't update the storage table " . mysqli_error($link);
                    }
                } else {
                    echo "<br>There's either not enough or something went from depends on -> " . mysqli_error($link);
                }
            } else {
                echo "<br>didn't add values to $tableName " . mysqli_error($link);
            }
		} else {
			echo "<br>didn't create table " . mysqli_error($link);
		}
	} else {
		echo "failed to select database " . mysqli_error($link);
	}

    mysqli_close($link);
?>

<br>

<a href="info.php">Back</a>
</html>