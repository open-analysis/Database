<html>
<?php 

	$link = mysqli_connect("localhost", "root", "3ng1neering");

    if(!$link){
            die("ERROR: Could not connect. " . mysqli_connect_error());
    }

	if (mysqli_select_db($link, "warehouse")){
		echo "selected warehouse database";

		$tableName = ["storage", "line", "floor"];
	    for ($i = 1; $i < 3; $i++){
	    	// getting the data to compare against
	        $sql = "SELECT * FROM ${tableName[$i]}";
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					// the comparison table that's "above" the first $result
					$sqlCheck = "SELECT * FROM ${tableName[$i-1]}";
					if ($resultCheck = mysqli_query($link, $sqlCheck)){
						// same process but getting the actual rows
						while ($row = mysqli_fetch_array($result)){ 
							// making sure the row exists in the table "above"
							if ($rowCheck = mysqli_fetch_array($resultCheck)) {
								if (($row['amount'] < (int)($row['max'] * .75)) && ($rowCheck['amount'] > 0)){
									echo "<br>Adding more product to ${tableName[$i]}";
									// creating the temp table
									$tempTable = $tableName[$i] . "_replinish";
									$sql = "CREATE TEMPORARY TABLE $tempTable (id INTEGER, amount INTEGER)";
									if (mysqli_query($link, $sql)){
						                echo "<br>Created temp table $tempTable";

						                $repAmount = (int)$row['max'] - (int)$row['amount'];
						                if ($repAmount > (int)$rowCheck['amount']) $repAmount = (int)$rowCheck['amount'];
						                echo "<br>$repAmount";

						                $sql = "INSERT INTO $tempTable (id, amount) VALUES (${row['id']}, $repAmount)";
						                if (mysqli_query($link, $sql)){
						                    echo "<br>Added: " . $_POST['amount'] . " of " . $_POST['id'] . " to $tempTable";
						                } else {
						                    echo "<br>didn't add values to $tempTable " . mysqli_error($link);
						                }
						            } else {
						                echo "<br>couldn't make temp table " . mysqli_error($link);
						            }
								} else {
									echo "<br>Something got messed up OR nothing to add" . mysqli_error($link);
								}
							} else {
								echo "<br>row doesn't exist in ${tableName[$i-1]} " . mysqli_error($link);
							}
						}
			    	} else {
			        	echo "<br>${tableName[$i-1]} doesn't exist";
			        }
				} else {
					echo "<br>There aren't any rows in this table " . mysqli_error($link);
				}
	    	} else {
	        	echo "<br>${tableName[$i]} doesn't exist";
	        }
	    }
	} else {
		echo "<br>Couldn't select warehouse database";
	}

?>
<br>
<a href="info.php">Back</a>
</html>