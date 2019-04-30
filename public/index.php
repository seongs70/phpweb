<?php
try{
	$pdo = new PDO('mysql:host=localhost; dbname=phpweb; charset=utf8', 'root', 'ghfjdk');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = 'SELECT `joketext` from `joke`';
	$result = $pdo->query($sql);
	
	$output = '데이터베이스 접속 성공';
} catch (PDOException $e){
	$output = '데이터베이스 서버에 접속할 수 없습니다.' . $e;
}

include __DIR__ . '/../templates/output.html.php';