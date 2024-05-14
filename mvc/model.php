<?php  

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

function setCorrectAnswerToQuestion($conn, $quiz_id, $question_id, $choice_id) {
	$sql = "
			SELECT 
				quizzes.quiz_id AS quiz_id,
				questions.description AS questionDescription,
				choices.description AS choiceDescription,
				choices.is_correct_answer AS isCorrectAnswer 
			FROM questions
			INNER JOIN quizzes ON questions.quiz_id = quizzes.quiz_id
			INNER JOIN choices ON questions.question_id = choices.question_id
			WHERE 
				quizzes.quiz_id = ?
				questions.question_id = ? 
				AND choices.is_correct_answer = 1
			GROUP BY questionDescription
			";

	$stmt = $conn->prepare($sql);
	$stmt->execute([$question_id]);

	if(!$stmt->rowCount() == 1) {
		$sql = "
				UPDATE choices 
				SET is_correct_answer = 1 
				WHERE choice_id = ?
				";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$choice_id]);
	}
	else {
		return false;
	}
}



?>