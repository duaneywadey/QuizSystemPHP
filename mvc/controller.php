<?php  

session_start();
require_once('dbConfig.php');
require_once('model.php');


if(isset($_POST['addNewQuizBtn'])) {
	$title = $_POST['title'];
	addNewQuiz($conn, $title);
	header('Location: ../index.php');
}

if (isset($_POST['addNewQuestionBtn'])) {
	$description = $_POST['description'];
	addAQuestion($conn, $description, $_GET['quiz_id']);
	header('Location: ../editQuiz.php?quiz_id=' . $_GET['quiz_id']);
}

if (isset($_POST['addNewChoiceBtn'])) {
	$description = $_POST['description'];
	addChoiceToQuestion($conn, $description, $_GET['quiz_id'], $_GET['question_id']);
	header('Location: ../addNewChoice.php?quiz_id=' . $_GET['quiz_id'] . '&question_id=' . $_GET['question_id']);
}

if (isset($_POST['setNewAnswerBtn'])) {
	$choiceFromQuestion = $_POST['choiceFromQuestion'];
	setCorrectAnswerToQuestion($conn, $_GET['question_id'],)	
}


// if(isset($_POST['submitAnsBtn'])) {
// 	$fav_language = $_POST['fav_language'];
// 	echo $fav_language;
// }


?>