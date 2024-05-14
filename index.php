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
	<h1>All Quizzes</h1>
	<?php include('links.php'); ?>
	
	<?php $allQuizzes = showAllQuizzes($conn); ?>
	<div class="container">
		<?php foreach ($allQuizzes as $row) { ?>
		<div class="quiz">
			<p><a href="takeQuiz.php"><?php echo $row['title']; ?></a></p>
		</div>	
		<?php } ?>
	</div>
</body>
</html>