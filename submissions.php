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
	<h1>Submissions</h1>
	<?php include('links.php'); ?>
	<table style="width: 100%; font-size: 1em;">
		<tr>
			<th>Quiz Name</th>
			<th>Score</th>
			<th>Date Completed</th>
		</tr>

		<?php $allQuizScores = showScoresByUserID($conn, $_SESSION['user_id']); ?>

		<?php foreach ($allQuizScores as $row) { ?>
		<tr>
			<td><?php echo $row['quiz_title']; ?></td>
			<td><?php echo $row['score']; ?> / <?php echo $row['no_of_items']; ?></td>
			<td><?php echo $row['date_added']; ?></td>
		</tr>
		<?php } ?>
	</table>	

</body>
</html>