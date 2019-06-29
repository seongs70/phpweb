<?php include_once('../common/lib/autoload.php'); ?>
<!DOCTYPE html>

<html lang="ko">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>페이지</title>

</head>

<body>

<h3>오토로드 테스트</h3>

<?php

$sideshow = new Sideshow('sideshow1');

$xm = new Xm('xm1');



echo $sideshow->getName();

echo "<br />";

echo $xm->getName();

?>

</body>

</html>
