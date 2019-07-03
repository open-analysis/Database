<html>

<?php 

	$link = mysqli_connect("localhost", "root", "3ng1neering");
    $tableName = "floor";

    if(!$link){
            die("ERROR: Could not connect. " . mysqli_connect_error());
    }

	if (mysqli_select_db($link, "warehouse")){
		echo "selected warehouse database";

		if(!empty($_GET["clear"])){
	    	// clears all the tables from the warehouse database
	    	$buttonText = "Show";
	    	$clearVal = "show";
	    	echo "<br>clearing tables<br>" . mysqli_error($link);
	    	
	    	$sql = "DROP DATABASE warehouse";
	    	if (mysqli_query($link, $sql)){
	    		echo "<br>Dropped database";
		    	$sql = "CREATE DATABASE warehouse";
		    	if (mysqli_query($link, $sql)){
		    		echo "<br>Created database";
		    	} else{
		    		echo "<br>couldn't create database " . mysqli_error($link);
		    	}	    		
	    	} else{
	    		echo "<br>couldn't drop database " . mysqli_error($link);
	    	}
	        echo "<br>Finished Deleting";
    	}else{
    		// prints all the tables from the warehouse database if the clear button hasn't been pressed
    		$buttonText = "Clear";
    		$clearVal = "clear";

    		$sql = "SHOW TABLES FROM warehouse";
			if ($result = mysqli_query($link, $sql)){
				echo "<br>Going through tables<br>";
				while ($table = mysqli_fetch_array($result)){
					echo "<br>" . $table[0];
				}
	    	} else {
	        	echo "<br>couldn't show tables" . mysqli_error($link);
	        }
	        // goes through what's actually in each table
	        echo "<br><br>";
	        $tableName = ["storage", "line", "floor"];
	        for ($i = 0; $i < 3; $i++){
	        	echo "<br>";
		        $sql = "SELECT * FROM $tableName[$i]";
				if ($result = mysqli_query($link, $sql)){
					echo "$tableName[$i]<br><table border=1> 
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
		        	echo "<br>$tableName[$i] doesn't exist";
		        }
	        }	        
    	}
	}
	mysqli_close($link);
?>

<?=
	$formText = "<form action=\"show_warehouse.php\" method=\"get\">
		<input type=\"hidden\" name=$clearVal value=1>
		<input type=\"submit\" value=$buttonText>
	</form>";	
?>
<br>
<a href="info.php">Back</a>
</html>