<ul>
	<li><a href="index.php">Home</a></li>
	<li><a href="submissions.php">View submissions</a></li>
	<?php if($_SESSION['is_admin'] == 1) { ?>
		<li><a href="addNewQuiz.php">Add new quiz</a></li>
		<li><a href="editTheQuizzes.php">Edit the quizzes</a></li>
		<li><a href="admin_requests.php">View admin requests</a></li>
	<?php } else { ?>
		<li><a href="request_as_admin.php">Apply as admin</a></li>
	<?php } ?>
	<li><a href="logout.php">Logout</a></li>
</ul>