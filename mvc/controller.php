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

	$choice_id = $_POST['choice_id'];

	if(!empty($choice_id)) {
		
		if(setCorrectAnswerToQuestion($conn, $_GET['quiz_id'], $_GET['question_id'], $choice_id)) {
			header('Location: ../addNewChoice.php?quiz_id=' . $_GET['quiz_id'] . '&question_id=' . $_GET['question_id']);
		}

		else {
			echo "Already has an answer!";
		}

	}

	else {
		echo "Dont leave the radio button empty!";
	}
}


if(isset($_POST['submitAnswersBtn'])) {
	$allInputs = $_POST;
	var_dump($allInputs);
	$questionsAndAnswers = array();
	$quizResult = array();
	$random = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$string = '';
	for ($i = 0; $i < 10; $i++) {
      $string .= $random [rand(0, strlen($random ) - 1)];
  	}
	foreach ($allInputs as $key => $value) {
		if($key == "submitAnswersBtn" && $value = "Submit") {
			break;
		}
		else {
			$questionsAndAnswers[$key] = $value; 			
		}
	}
	foreach ($questionsAndAnswers as $question => $answer) {
		insertNewSubmission($conn, $string, $_GET['quiz_id'], $question, $answer);
		$isCorrect = validateAnswer($conn, $question, $answer);
		array_push($quizResult, $isCorrect);
	}
	$counter = 0;
	foreach ($quizResult as $key => $value) {
		if($value == true) {
			$counter+=1;
		}
	}
	echo "<br>Score: " . $counter . "/" . count($quizResult) . "<br>"; 

}

?>