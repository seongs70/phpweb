<?php
include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';
try{
    if(isset($_POST['joketext'])){
        updateJoke($pdo, $_POST['jokeid'], $_POST['joketext'], 1);

        header('location: jokes.php');
    } else {
        $joke = getJoke($pdo, $_GET['id']);
    	$title = '글 수정';
    	ob_start();
    	include  __DIR__ . '/../templates/editjoke.html.php'	;
    	$output = ob_get_clean();
    }
} catch (PDOException $e){
    $output = '데이터베이스 서버에 접속할 수 없습니다.' . $e;
}
include __DIR__ . '/../templates/layout.html.php';
