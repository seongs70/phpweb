<?php
try{
	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../includes/DatabaseFunctions.php';
	$output = '';
	$sql = 'select `joke`.`id`, `joketext`, `name`, `email` from `joke` inner join `author` on `authorid` = `author`.`id`';
	// query() 메서드는SQL 쿼리를 전달받아 데이터베이스 서버로 전송해 PDOStatement 객체를 반환한다
	// 쿼리 실행 결과로 반환된 모든 로우의 목록이 이 객체에 담긴다.

	$jokes = allJokes($pdo);
	
	$title = '목록';

	$totalJokes = totalJokes($pdo);

	ob_start();
	include  __DIR__ . '/../templates/jokes.html.php'	;
	$output = ob_get_clean();

} catch (PDOException $e){
	$output = '데이터베이스 서버에 접속할 수 없습니다.' . $e;
}

include __DIR__ . '/../templates/layout.html.php';
