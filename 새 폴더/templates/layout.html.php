<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="jokes.css">
	<title><?= $title;?></title>
</head>
<body>
	<header>
		<h1>php web</h1>
	</header>
	<nav>
		<ul>
		<li><a href="index.php">Home</a></li>	
		<li><a href="jokes.php">목록</a></li>
		<li><a href="addjoke.php">등록</a></li>	
		</ul>	
	</nav>
	<main>
		<?= $output ?>
	</main>
	<footer>
		&copy; KSH 2019
	</footer>
</body>
</html>