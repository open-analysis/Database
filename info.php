<html>
<body>
	<title>Test</title>

	<?php
		$link = mysqli_connect("localhost", "root", "3ng1neering");

		if(!$link){
			die("ERROR: Could not connect. " . mysqli_connect_error());
			echo "<br>";
		}

		$sql = "CREATE DATABASE IF NOT EXISTS warehouse";
	        if (mysqli_query($link, $sql)){
	                echo "Database warehouse created";
	        } else {
	                echo "Database failed to be created\n" . mysqli_error($link);
	        }

		echo "<br>";
	/*
		$sql = "DROP DATABASE IF EXISTS demo";
		if (mysqli_query($link, $sql)){
			echo "Database deleted";
		} else {
			echo "Database failed to delete\n" . mysqli_error($link);
		}
	*/
	//	mysqli_close($link);

		?>

		<h2>Add items to warehouse:</h2>
		<form method="POST" action="add_warehouse.php">
		Warehouse ID: <input type="text" name="id">
		Warehouse Amount: <input type="text" name="amount">
		<input type="submit" value="Submit">
		</form>

		<br>

        <h2>Add items to line:</h2>
	        <form method="POST" action="add_line.php">
	        Warehouse ID: <input type="text" name="id">
	        Warehouse Amount: <input type="text" name="amount">
	        <input type="submit" value="Submit">
	        </form>

		<br>

		<h2>Add items to floor:</h2>
	        <form method="POST" action="add_floor.php">
	        Warehouse ID: <input type="text" name="id">
	        Warehouse Amount: <input type="text" name="amount">
	        <input type="submit" value="Submit">
	        </form>

        <br>

        <form method="get" action="show_warehouse.php">
	        <input type="submit" value="Show Tables in Warehouse">
        </form>

		<?php 
			mysqli_close($link);
		?>
</body>
</html>
