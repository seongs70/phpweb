<?php

if(isset($_POST['joketext'])){
	try{
		include __DIR__ . '/../includes/DatabaseConnection.php';
		include __DIR__ . '/../includes/DatabaseFunctions.php';

		insertJoke($pdo, $_POST['joketext'], 1);

		header('location: jokes.php');
	} catch (PDOException $e){
		$output = '데이터베이스 서버에 접속할 수 없습니다.' . $e;
	}
} else {
	$title = '목록';
	ob_start();
	include  __DIR__ . '/../templates/addjoke.html.php'	;
	$output = ob_get_clean();
}
include __DIR__ . '/../templates/layout.html.php';
