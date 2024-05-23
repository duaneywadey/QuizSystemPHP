<?php  
session_start();
require_once('mvc/dbConfig.php');
require_once('mvc/model.php');
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>
	<style>
		body {
			font-size: 2em;
			font-family: Arial;
		}
		input[type="text"] {
			display: block;
			height: auto;
			width: 500px;
			margin-top: 10px;
			font-size: 2em;
		}

		input[type="password"] {
			display: block;
			height: auto;
			width: 500px;
			margin-top: 10px;
			font-size: 2em;
		}

		input[type="submit"] {
			margin-top: 10px;
			height: auto;
			width: 300px;
			font-size: 2em;
		}
	</style>
</head>
<body>
	<h1>Quiz System</h1>
	<h3>Register here</h3>
	<form action="mvc/controller.php" method="POST">
		<input type="text" name="username" placeholder="Username here">	
		<input type="password" name="password" placeholder="Password here">	
		<input type="submit" value="Submit">
	</form>
</body>
</html>