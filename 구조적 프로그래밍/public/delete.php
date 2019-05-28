<?php try{
    include __DIR__ . '/../includes/DatabaseConnection.php';
    include __DIR__ . '/../includes/DatabaseFunctions.php';

    deleteJoke($pdo, $_POST['id']);

    header('location: jokes.php');
} catch (PDOException $e){
    $output = '데이터베이스 서버에 접속할 수 없습니다.' .
    $e->getMessage() . ', 위치: ' . $e->getFIle() . ':' . $e->getLine();
}
include __DIR__ . '/../templates/layout.html.php';
