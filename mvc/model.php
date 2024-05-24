<?php  

require_once('dbConfig.php');

function registerAUser($conn, $username, $password) {

	$sql = "SELECT * FROM users WHERE username=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$username]);

	if($stmt->rowCount()==0) {
		$sql = "INSERT INTO users (username,password) VALUES(?,?)";
		$stmt = $conn->prepare($sql);
		return $stmt->execute([$username, $password]);
	}
	else {
		return false;
	}

	
}

function loginUser($conn, $username, $password) {

	// Select if username exists first
	$sql = "SELECT * FROM users WHERE username=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$username]);

	// If it exists, get all values from the row
	if($stmt->rowCount() == 1) {

		// This returns associative array; fetch() returns only one row while fetchAll() returns multiple rows
		$userInfoRow = $stmt->fetch();

		// Get individual values from userInfoRow
		$user_id = $userInfoRow['user_id'];
		$is_admin = $userInfoRow['is_admin'];
		$username = $userInfoRow['username'];
		$hashedPassword = $userInfoRow['password'];

		// Verify if the inputted passwword is correct; '$password' is the variable that stores the inputted password while '$hashedPassword' is the variable that stores the password as stated from the database.  
		if(password_verify($password, $hashedPassword)) {

			// If the inputted password and password from the database are both same, store user info as session variables. 
			$_SESSION['user_id'] = $user_id;
			$_SESSION['is_admin'] = $is_admin;
			$_SESSION['username'] = $username;
			return true;
		}
	}
}

function requestAsAdmin($conn, $user_id, $admin_request_letter) {

	$sql = "SELECT * FROM admin_requests WHERE user_id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$user_id]);

	if($stmt->rowCount() == 0) {
		$sql = "INSERT INTO admin_requests (user_id, admin_request_letter) 
			VALUES(?,?)
			";
		$stmt = $conn->prepare($sql);
		return $stmt->execute([$user_id, $admin_request_letter]);
	}

	else {
		return false;
	}
	
}

function showAllAdminRequests($conn) {
	$sql = "
			SELECT
				admin_requests.admin_request_id AS admin_request_id, 
				users.user_id AS user_id,
				users.username AS username,
				admin_requests.admin_request_letter AS admin_request_letter
			FROM users
			JOIN admin_requests ON users.user_id = admin_requests.user_id
			WHERE admin_requests.is_approved = 0
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	return $stmt->fetchAll();
}

function approveAdminRequest($conn, $admin_user_id, $admin_request_id) {
	$sql = "
			UPDATE admin_requests 
			SET approved_by = ?, 
			is_approved = 1 
			WHERE admin_request_id = ?
			";
	$stmt = $conn->prepare($sql);
	return $stmt->execute([$admin_user_id, $admin_request_id]);
}

function setUserToAdmin($conn, $user_id) {
	$sql = "UPDATE users SET is_admin = 1 WHERE user_id = ?";
	$stmt = $conn->prepare($sql);
	return $stmt->execute([$user_id]);
}

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

function validateAnswer($conn, $question_id, $choice_id) {
	$sql = "SELECT choice_id 
			FROM choices 
			WHERE question_id = ? 
			AND is_correct_answer = 1
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$question_id]);
	$answer = $stmt->fetch();
	$correctAns = $answer['choice_id'];

	if ($choice_id == $correctAns) {
		return true;
	}
	else {
		return false;
	}

}

function insertNewQuizScore($conn, $user_id, $quiz_id, $score, $no_of_items) {
	$sql = "INSERT INTO quiz_scores (user_id, quiz_id, score, no_of_items) 
	VALUES(?,?,?,?)
			";
	$stmt = $conn->prepare($sql);
	return $stmt->execute([$user_id, $quiz_id, $score, $no_of_items]);
}

function showScoresByUserID($conn, $user_id) {
	$sql = "SELECT 
				users.user_id AS user_id,
				users.username AS username,
				quizzes.title AS quiz_title,
				quiz_scores.score AS score,
				quiz_scores.no_of_items AS no_of_items,
				quiz_scores.date_added AS date_added
			FROM quiz_scores
			JOIN users ON quiz_scores.user_id = users.user_id
			JOIN quizzes ON quiz_scores.quiz_id = quizzes.quiz_id
			WHERE users.user_id = ?
			ORDER BY quiz_scores.date_added
			";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$user_id]);
	return $stmt->fetchAll();
}
 


?>