<?php  

require_once('dbConfig.php');

function showAllQuizzes($conn) {
	$sql = "SELECT * FROM quizzes";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	return $stmt->fetchAll();
}


function addNewQuiz($conn, $title) {
	$sql = "
			INSERT INTO quizzes (title) 
			VALUES (?)
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$title]);
}

function getQuizByID($conn, $quiz_id) {
	$sql = "
			SELECT * FROM quizzes 
			WHERE quiz_id = ?
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$quiz_id]);
	return $stmt->fetch();
}

function addAQuestion($conn, $description, $quiz_id) {
	$sql = "
			INSERT INTO questions (description, quiz_id)
			VALUES(?,?)
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$description, $quiz_id]);
}

function showAllQuestionsByQuizID($conn, $quiz_id) {
	$sql = "SELECT * FROM questions 
			WHERE quiz_id = ? 
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$quiz_id]);
	return $stmt->fetchAll();
}

function showAllChoicesByQuestionID($conn, $question_id) {
	$sql = "SELECT * FROM choices 
			WHERE question_id = ?
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$question_id]);
	return $stmt->fetchAll();
}

function getQuestionByID($conn, $question_id) {
	$sql = "SELECT * FROM questions
			WHERE question_id = ?
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$question_id]);
	return $stmt->fetchAll();
}

function addChoiceToQuestion($conn, $quiz_id, $question_id, $description) {
	$sql = "
			INSERT INTO choices (description, quiz_id, question_id)
			VALUES(?,?,?)
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$quiz_id, $question_id, $description]);
}

function showAllChoicesToEachQuestion($conn, $quiz_id, $question_id) {
	$sql = "
			SELECT * FROM choices 
			WHERE quiz_id = ? AND 
			question_id = ? 
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$quiz_id, $question_id]);
	return $stmt->fetchAll();
}

function checkIfCorrectAns($conn, $question_id) {
	$sql = "SELECT * FROM choices 
			WHERE question_id = ? 
			AND is_correct_answer = 1
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$question_id]);
	$allData = $stmt->fetchAll();
	$correctAnswers = array();
	foreach ($allData as $row) {
		array_push($correctAnswers, $row['choice_id']);
	}
	return $correctAnswers;
}

function checkIfCorrectAnsExists($conn, $question_id) {
	$sql = "SELECT * FROM choices
			WHERE is_correct_answer = 1 
			AND question_id = ?
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$question_id]);
	$rowCount = $stmt->rowCount();
	return $rowCount;
}

function makeOtherChoicesWrong($conn, $question_id) {
	$sql = "UPDATE choices 
			SET is_correct_answer = 0
			WHERE question_id = ?  
			";
	$stmt = $conn->prepare($sql);
	return $stmt->execute([$question_id]);
}

function setCorrectAnswerToQuestion($conn, $quiz_id, $question_id, $choice_id) {

	if(makeOtherChoicesWrong($conn, $question_id)) {
		$sql = "UPDATE choices 
			SET is_correct_answer = 1 
			WHERE choice_id = ? 
			AND question_id = ?
			";
		$stmt = $conn->prepare($sql);
		return $stmt->execute([$choice_id, $question_id]);
	}
	else {
		return false;
	}	 
	
}

function findCorrectAnswerToQuestion($conn, $question_id) {
	$sql = "
			SELECT * FROM choices 
			WHERE question_id = ? 
			AND is_correct_answer = 1
			";

	$stmt = $conn->prepare($sql);
	$stmt->execute([$question_id]);
	$correctAns = $stmt->fetch();
	return $correctAns;
}

function insertNewSubmission($conn, $attempt_id, $quiz_id, $question_id, $choice_id) {
	$sql = "
			INSERT INTO submissions (attempt_id, quiz_id, question_id, choice_id)
			VALUES(?,?,?,?)
			";
	$stmt = $conn->prepare($sql);
	return $stmt->execute([$attempt_id, $quiz_id, $question_id, $choice_id]);
}

// To find correct answer
// $rowCountTest = findCorrectAnswerToQuestion($conn,8);
// echo $rowCountTest['description'];
// echo "<br>" . $rowCountTest['choice_id'];

?>