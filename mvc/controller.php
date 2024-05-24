<?php  

session_start();
require_once('dbConfig.php');
require_once('model.php');

if(isset($_POST['loginBtn'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	if(!empty($username) && !empty($password) && loginUser($conn, $username, $password)) {
		header('Location: ../index.php');
	}

	else {
		header('Location: ../login.php');
	}
}

if(isset($_POST['registerBtn'])) {
	$username = $_POST['username'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

	if(!empty($username) && !empty($password) && registerAUser($conn, $username, $password)){
		header('Location: ../login.php');
	}
	else {
		header('Location: ../register.php');
	}
}

if(isset($_POST['requestAsAdminBtn'])) {
	$adminRequestLetter = $_POST['adminRequestLetter'];
	if(requestAsAdmin($conn, $_SESSION['user_id'], $adminRequestLetter)) {
		header('Location: ../index.php');
	}
	else {
		header('Location: ../request_as_admin.php');
	}
}

if(isset($_POST['acceptAdminRequestBtn'])) {
	if(approveAdminRequest($conn, $_SESSION['user_id'], $_POST['admin_request_id'])) {
		if(setUserToAdmin($conn, $_POST['user_id'])) {
			header('Location: ../admin_requests.php');
		}
	}
}

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
	
	if(insertNewQuizScore($conn, $_SESSION['user_id'], $_GET['quiz_id'], $counter, count($quizResult))) {
		// header("Location: ../displayResult.php?counter=" . $counter . "&quizResult=" . count($quizResult));
		header("Location: ../submissions.php");
	}


}

?>