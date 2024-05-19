<?php  
session_start();
require_once('mvc/dbConfig.php');
require_once('mvc/model.php');

$answersList = array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<?php $quizInfo = getQuizByID($conn, $_GET['quiz_id']); ?>
	<h1><?php echo $quizInfo['title']; ?></h1>
	<?php include('links.php'); ?>

	<div class="question">
		<ol>
		<?php $allQuestions = showAllQuestionsByQuizID($conn, $_GET['quiz_id']); ?>
		<?php foreach ($allQuestions as $row) { ?>
			<li>
				<form action="mvc/controller.php?quiz_id=<?php echo $_GET['quiz_id']; ?>" method="POST">
					<p><?php echo $row['description']; ?></p>

					<?php $allChoices = showAllChoicesByQuestionID($conn, $row['question_id']); ?>
					<?php foreach ($allChoices as $choice) { ?>
					<div class="choice">
						<?php array_push($answersList, $choice['choice_id']); ?>
						<input type="radio" id="javascript" name="<?php echo $row['question_id']; ?>" value="<?php echo $choice['choice_id']; ?>">
						<label for="javascript"><?php echo $choice['description']; ?></label>
					</div>
					<?php } ?>
			</li>
		<?php } ?>
		</ol>
		<p><input type="submit" value="Submit" name="submitAnswersBtn"></p>
		</form>
	</div>
</body>
</html>