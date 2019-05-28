<?php
try{
	include __DIR__ . '/../includes/DatabaseConnection.php';
	$sql = 'DELETE FROM `joke` WHERE `id` = :id';
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();
    header('location: jokes.php');

} catch (PDOException $e){
	$output = '데이터베이스 서버에 접속할 수 없습니다.' . $e;
}

include __DIR__ . '/../templates/layout.html.php';
