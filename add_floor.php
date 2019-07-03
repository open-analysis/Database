<html>
<?php
    $link = mysqli_connect("localhost", "root", "3ng1neering");
    $tableName = "floor";

        if(!$link){
                die("ERROR: Could not connect. " . mysqli_connect_error());
        }

    if (mysqli_select_db($link, "warehouse")){
        echo "selected warehouse database";
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (id INTEGER, amount INTEGER)";
        if (mysqli_query($link, $sql)){
            echo "<br>created table $tableName";
            // add item (takes them from line & puts them into the line)
            // checks to see if there's enough in line
            $sql = "SELECT amount FROM line WHERE id = " . $_POST['id'];
            if ($result = mysqli_query($link, $sql)){
                if ($result <= $_POST['amount']){
                    echo "<br>There's enough products on the line";
                    // removes the amount indicated from the floor
                    $sql = "UPDATE line SET amount = " . $result - $amount . " WHERE id = " . $_POST['id'];
                    if (mysqli_query($link, $sql)){
                        // adds said amount to the floor table
                        $sql = "INSERT INTO $tableName (id, amount) VALUES (" . $_POST['id'] . ", " . $_POST['amount'] . ")";
                        if (mysqli_query($link, $sql)){
                            echo "<br>Added: " . $_POST['amount'] . " of " . $_POST['id'] . " to $tableName";
                        } else {
                            echo "<br>didn't add values to $tableName " . mysqli_error($link);
                        }
                    } else {
                        echo "<br>couldn't update the line table " . mysqli_error($link);
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