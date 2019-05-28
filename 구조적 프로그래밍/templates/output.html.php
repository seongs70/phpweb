<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>아웃풋</title>
</head>
<body>
	<?= $output; ?>
	<?php if (isset($error)):?>
	<p>
		<?= $error; ?>
	</p>
	<?php else: ?>
	
<?php endif; ?>
</body>
</html>