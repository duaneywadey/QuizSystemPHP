<?php  
session_start();
require_once('mvc/dbConfig.php');
require_once('mvc/model.php');

if(!isset($_SESSION['username'])) {
	header('Location: login.php');
}
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<h1>Request as Admin</h1>
	<?php include('links.php'); ?>
	<form action="mvc/controller.php" method="POST">
		<textarea style="font-size: 2em;" rows="4" cols="50" placeholder="Why become an admin?" name="adminRequestLetter"></textarea>	
		<input type="submit" value="Submit" name="requestAsAdminBtn">
	</form>
</body>
</html>