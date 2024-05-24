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
	<h1>Admin Requests</h1>
	<?php include('links.php'); ?>
	<?php $allRequests = showAllAdminRequests($conn) ?>
	<?php foreach ($allRequests as $row) { ?>
	<div class="request" style="border-style: solid; margin-top: 10px;">
		<h1><?php echo $row['username']; ?></h1>
		<p><?php echo $row['admin_request_letter']; ?></p>
		<form action="mvc/controller.php" method="POST">
			<input type="hidden" value="<?php echo $row['user_id']; ?>" name="user_id">
			<input type="hidden" value="<?php echo $row['admin_request_id']; ?>"name="admin_request_id">
			<input type="submit" value="Accept" name="acceptAdminRequestBtn">
		</form>
		<form action="#">
			<input type="submit" value="Reject" style="background-color: red;">
		</form>
	</div>
	<?php } ?>
</body>
</html>