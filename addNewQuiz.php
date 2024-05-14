<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<h1>Add New Quiz</h1>
	<?php include('links.php'); ?>
	<form action="mvc/controller.php" method="POST">
		<p><input type="text" name="title"></p>
		<p><input type="submit" value="Submit" name="addNewQuizBtn"></p>
	</form>
</body>
</html>