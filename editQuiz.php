<?php  
session_start();
require_once('mvc/dbConfig.php');
require_once('mvc/model.php');
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<?php include('links.php'); ?>
	
	<div class="addAQuestion">
		<h1>
			<?php 

			$getQuizByID = getQuizByID($conn, $_GET['quiz_id']);
			foreach ($getQuizByID as $row) {
				echo $row['title'];
			}

			?>
		</h1>
		<h3>Add a question</h3>
		<form action="mvc/controller.php?quiz_id=<?php echo $_GET['quiz_id']; ?>" method="POST">
			<input type="text" name="description">
			<input type="submit" value="Submit" name="addNewQuestionBtn">
		</form>
	</div>

	<?php $showAllQuestionsByQuizID = showAllQuestionsByQuizID($conn, $_GET['quiz_id']);?>
	<div class="questionsList">
		<ol>
			<?php foreach ($showAllQuestionsByQuizID as $row) { ?>
				<li><a href="addNewChoice.php?quiz_id=<?php echo $_GET['quiz_id']?>&question_id=<?php echo $row['question_id']; ?>"><?php echo $row['description']; ?></a></li>
			<?php } ?>	
		</ol>
	</div>	
</body>
</html>