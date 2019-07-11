<html>
<?php
    $errSt = "<h1 class=\"error\">";
    $errEn = "</h1>";

	$link = mysqli_connect("localhost", "root", "3ng1neering");
    $tableName = "line";

        if(!$link){
                die("ERROR: Could not connect. " . mysqli_connect_error());
        }

	if (mysqli_select_db($link, "warehouse")){
		echo "selected warehouse database";
        // check to see if the line table exists
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (id INTEGER PRIMARY KEY, amount INTEGER, max INTEGER DEFAULT 200)";
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


                        /*$sql = "INSERT INTO $tableName (id, amount) VALUES (" . $_POST['id'] . ", " . $_POST['amount'] . ")";
                        if (mysqli_query($link, $sql)){
                            echo "<br>Added: " . $_POST['amount'] . " of " . $_POST['id'] . " to $tableName";
                        } else {
                            echo "<br>didn't add values to $tableName " . mysqli_error($link);
                        }*/
                    } else {
                        echo $errSt . "<br>couldn't update the storage table " . mysqli_error($link) . $errEn;
                    }
                } else {
                    echo $errSt . "<br>There's either not enough or something went from depends on -> " . mysqli_error($link) . $errEn;
                }
            } else {
                echo $errSt . "<br>didn't add values to $tableName " . mysqli_error($link) . $errEn;
            }
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