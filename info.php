<!DOCTYPE html>
<html>
<head>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
		  $("#test").click(function(){
		    $(this).hide();
		  });
		  $("*").mouseenter(function(){
		  	$(".error").css("color", "red");
		  });
		});
	</script>
</head>
<body>
	<title>Test</title>

	<?php
		$errSt = "<h1 class=\"error\">";
		$errEn = "</h1>";
		$link = mysqli_connect("localhost", "root", "password");

		if(!$link){
			die(errSt . "ERROR: Could not connect. " . mysqli_connect_error()) . $errEn;
			echo "<br>";
		}

		$sql = "CREATE DATABASE IF NOT EXISTS warehouse";
        if (mysqli_query($link, $sql)){
                //echo "Database warehouse created";
        } else {
                echo $errSt ."Database failed to be created" . mysqli_error($link) . $errEn;
        }

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
		        <!-- Max Amount (Default 200): <input type="text" name="max"> -->
		        <input type="submit" value="Submit">
	        </form>

		<br>

		<h2>Add items to floor:</h2>
	        <form method="POST" action="add_floor.php">
		        Warehouse ID: <input type="text" name="id">
		        Warehouse Amount: <input type="text" name="amount">
		       <!-- Max Amount (Default 100): <input type="text" name="max"> -->
		        <input type="submit" value="Submit"> 
	        </form>

        <br>

        <form method="get" action="show_warehouse.php">
	        <input type="submit" value="Show Tables in Warehouse">
        </form>

        <br>

        <form method="get" action="replenish.php">
	        <input type="submit" value="Replenish">
        </form>

        <br>

        <button id="test" class="error">Test</button>


		<?php 
			mysqli_close($link);
		?>
</body>
</html>
