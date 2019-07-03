<?php 

	$link = mysqli_connect("localhost", "root", "3ng1neering");
    $currentTable = "";

    if(!$link){
            die("ERROR: Could not connect. " . mysqli_connect_error());
    }

	if (mysqli_select_db($link, "warehouse")){
		echo "selected warehouse database";

		if (mysqli_num_rows(mysqli_query($link,$sql)) > 0){
			$tableName = ["storage", "line", "floor"];
		    for ($i = 1; $i < 3; $i++){
		        $sql = "SELECT * FROM $tableName[$i]";
				if ($result = mysqli_query($link, $sql)){
					$sqlCheck = "SELECT * FROM $tableName[$i-1]";
					if ($resultCheck = mysqli_query($link, $sqlCheck)){
						while ($row = mysqli_fetch_array($result)){
							$rowCheck = mysqli_fetch_array($resultCheck);
							if ($row['amount'] < (int)($row['max'] * .75) && $row['amount'] < $rowCheck['amount']){
								$currentTable = $tableName[$i] . "_replinish";
								echo "<br>Adding more product to $tableName[$i]";
								$sql = "CREATE TEMPORARY TABLE $currentTable (id INTEGER, amount INTEGER)";
								if (mysqli_query($link, $sql)){
					                echo "<br>Created temp table $currentTable";
					                $repAmount = (int)$rowCheck['amount'] - (int)$row['amount'];
					                if ($repAmount > (int)$row['max']) $repAmount = (int)$row['max'];
					                $sql = "INSERT INTO $currentTable (id, amount) VALUES ($row['id'], $repAmount)";
					                if (mysqli_query($link, $sql)){
					                    echo "<br>Added: " . $_POST['amount'] . " of " . $_POST['id'] . " to $tableName";
					                } else {
					                    echo "<br>didn't add values to $tableName " . mysqli_error($link);
					                }
					            } else {
					                echo "<br>couldn't make temp table " . mysqli_error($link);
					            }
							} else {
								echo "<br>Something got messed up " . mysqli_error($link);
							}
						}					
			    	} else {
			        	echo "<br>$tableName[$i] doesn't exist";
			        }

		    	} else {
		        	echo "<br>$tableName[$i] doesn't exist";
		        }
		    }
		}
	} else {
		echo "<br>Couldn't select warehouse database";
	}

?>