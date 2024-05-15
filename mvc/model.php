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
	return $stmt->fetchAll();
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


function setCorrectAnswerToQuestion($conn, $quiz_id, $question_id, $choice_id) {
	$sql = "UPDATE choices 
			SET is_correct_answer = 1 
			WHERE choice_id = ? 
			AND question_id = ?
			";
	$stmt = $conn->prepare($sql);
	return $stmt->execute([$choice_id, $question_id]);
}


?>