<html>
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
		  $("*").mouseenter(function(){
		  	$(".error").css("color", "red");
		  });
		});
	</script>
<?php 
	$errSt = "<h1 class=\"error\">";
	$errEn = "</h1>";
	$link = mysqli_connect("localhost", "root", "password");

    if(!$link){
            die("ERROR: Could not connect. " . mysqli_connect_error());
    }

	$sql = "CREATE DATABASE IF NOT EXISTS replenishes";
    if (mysqli_query($link, $sql)){
            echo "Database replenishes created";
    } else {
            echo $errSt . "Database failed to be created" . mysqli_error($link) . $errEn;
    }

    if(empty($_GET["clear"])){
    	if (mysqli_select_db($link, "warehouse")){
		echo "<br>selected warehouse database";

		$sql = "SHOW TABLES FROM warehouse";
		if ($result = mysqli_query($link, $sql)){
			$count = 0;
			while ($table = mysqli_fetch_array($result)){
				$tables[$count] = $table[0];
				$count++;
			}
		} else {
        	echo $errSt . "<br>couldn't show tables" . mysqli_error($link) . $errEn;
        }

		$tables = array_reverse($tables);
	    for ($i = 1; $i < 3; $i++){
	    	if (mysqli_select_db($link, "warehouse")){
				echo "<br>selected warehouse database";

				// getting the data
		        $sql = "SELECT * FROM ${tables[$i]}";
				if ($result = mysqli_query($link, $sql)){
					if (mysqli_num_rows($result) > 0){
						// the comparison table that's "above" the first $result
						$sqlCheck = "SELECT * FROM ${tables[$i-1]}";
						if ($resultCheck = mysqli_query($link, $sqlCheck)){
							// same process but getting the actual rows
							while ($row = mysqli_fetch_array($result)){

								// making sure the row exists in the table "above"
								if ($rowCheck = mysqli_fetch_array($resultCheck)) {
									if (($row['amount'] < (int)($row['max'] * .75)) && ($rowCheck['amount'] > 0)){
										echo "<br><br>Adding more product to ${tables[$i]}";
										
										if (mysqli_select_db($link, "replenishes")){
											echo "<br>Selected replenishes database";
											// creating the temp table
											$tempTable = $tables[$i] . "_replenish";
											ECHO "<br>" .$tempTable;
											$sql = "CREATE TABLE IF NOT EXISTS $tempTable (id INTEGER PRIMARY KEY, amount INTEGER)";
											if (mysqli_query($link, $sql)){
								                echo "<br>Created temp table $tempTable";

								                $repAmount = (int)$row['max'] - (int)$row['amount'];
								                if ($repAmount > (int)$rowCheck['amount']) $repAmount = (int)$rowCheck['amount'];
								                echo "<br>$repAmount for $tempTable";

								                $sql = "INSERT INTO $tempTable (id, amount) VALUES (${row['id']}, $repAmount)";
								                if (mysqli_query($link, $sql)){
								                    echo "<br>Added: " . $repAmount . " of " . $row['id'] . " to $tempTable";
								                } else {
								                    if (mysqli_error($link) == "Duplicate entry '1' for key 'PRIMARY'"){
								            		echo "";
									            	} else{
								            			echo $errSt . "<br>didn't add values to $tempTable " . mysqli_error($link) . $errEn;
								            		}
								                }
								            } else {
								            	echo $errSt . "<br>couldn't make table " . mysqli_error($link) . $errEn;
								            }
										} else {
											echo $errSt . "<br>couldn't select replenishes database" . mysqli_error($link) . $errEn;
										}
									} else {
										echo $errSt . "<br>Something got messed up OR nothing to add" . mysqli_error($link) . $errEn;
									}
								} else {
									echo $errSt . "<br>row doesn't exist in ${tables[$i-1]} " . mysqli_error($link) . $errEn;
								}							
							}
				    	} else {
				        	echo $errSt . "<br>${tables[$i-1]} doesn't exist " . $errEn;
				        }
					} else {
						echo $errSt . "<br>There aren't any rows in this table " . mysqli_error($link) . $errEn;
					}
		    	} else {
		        	echo $errSt . "<br>${tables[$i]} doesn't exist" . mysqli_error($link) . $errEn;
		        }
			} else {
				echo $errSt . "<br>couldn't select warehouse database" . mysqli_error($link) . $errEn;
			}
	    	
	    }
	} else {
		echo $errSt . "<br>Couldn't select warehouse database" . $errEn;
	}	
	unset($tables);
}

// SHOWING THE TABLES
	if (mysqli_select_db($link, "replenishes")){
		echo "<br><br><br><h3>selected replenishes database to display</h3>";

		if(!empty($_GET["clear"])){
	    	// clears all the tables from the replenishes database
	    	$buttonText = "Replenish";
	    	$clearVal = "show";

	    	echo "<br>clearing tables<br>" . mysqli_error($link);
	    	
	    	$sql = "DROP DATABASE replenishes";
	    	if (mysqli_query($link, $sql)){
	    		echo "<br>Dropped database";    		
	    	} else{
	    		echo $errSt . "<br>couldn't drop database " . mysqli_error($link) . $errEn;
	    	}
	        echo "<br>Finished Deleting";
    	}else{
    		// prints all the tables from the replenishes database if the clear button hasn't been pressed
    		$buttonText = "Clear";
    		$clearVal = "clear";

    		$sql = "SHOW TABLES FROM replenishes";
			if ($result = mysqli_query($link, $sql)){

				$count = 0;
				while ($table = mysqli_fetch_array($result)){
					$tables[$count] = $table[0];
					$count++;
				}

	    	} else {
	        	echo $errSt . "<br>couldn't show tables" . mysqli_error($link) . $errEn;
	        }
	        // goes through what's actually in each table
	        $tables = array_reverse($tables);
	        for ($i = 0; $i < count($tables); $i++){
	        	echo "<br>";
		        $sql = "SELECT * FROM $tables[$i]";
				if ($result = mysqli_query($link, $sql)){
					echo "$tables[$i]<br><table border=1> 
					<tr>
					<th>ID</th>
					<th>Amount</th>
					</tr>";
					while ($row = mysqli_fetch_array($result)){
						echo "<tr>";
						echo "<td>" . $row['id'] . " </td>";
						echo "<td>" . $row['amount'] . " </td>";
						echo "</tr>";
					}
					echo "</table>";
		    	} else {
		        	echo $errSt . "<br>${tables[$i]} doesn't exist" . $errEn;
		        }
	        }
    	}
	}
	mysqli_close($link);
?>
<br>
<br>
<?=
	$formText = "<form action=\"replenish.php\" method=\"get\">
		<input type=\"hidden\" name=$clearVal value=1>
		<input type=\"submit\" value=$buttonText>
	</form>";	
?>
<br>
<br>
<button id="return" onclick="window.location.href = 'info.php';">Back</button>
</html>