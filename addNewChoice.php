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
	<!-- <h1><?php echo $_GET['quiz_id']; ?></h1> -->
	<!-- <h1><?php echo $_GET['question_id']; ?></h1> -->
	<div class="addAQuestion">
		<h1>
			<?php 

			$getQuizByID = getQuestionByID($conn, $_GET['question_id']);
			foreach ($getQuizByID as $row) {
				echo $row['description'];
			}

			?>
		</h1>
		<h3>Add choices</h3>
		<form action="mvc/controller.php?quiz_id=<?php echo $_GET['quiz_id']; ?>&question_id=<?php echo $_GET['question_id']; ?>" method="POST">
			<input type="text" name="description">
			<input type="submit" value="Submit" name="addNewChoiceBtn">
		</form>
	</div>

	<div class="choices">
		<h1>Choices </h1>
		<?php $showAllChoicesByQuestion = showAllChoicesToEachQuestion($conn, $_GET['quiz_id'], $_GET['question_id']); ?>
		<form action="mvc/controller.php?quiz_id=<?php echo $_GET['quiz_id'] ?>&question_id=<?php echo $_GET['question_id']; ?>" method="POST">
			<?php foreach ($showAllChoicesByQuestion as $row) { ?>
				<div class="choice">
					<input type="radio" id="child" name="choiceFromQuestion" value="child">
					<input type="hidden" value="<?php echo $row['choice_id'] ?>" name="choice">
					<label for="child"><?php echo $row['description']; ?></label><br>	
				</div>
			<?php } ?>
			<input type="submit" value="Submit" name="setNewAnswerBtn">
		</form>		
	</div>
</body>
</html>