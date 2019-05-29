<?php

try {
  include __DIR__ . '/../includes/DatabaseConnection.php';
  include __DIR__ . '/../includes/DatabaseFunctions.php';

  $result = findAll($pdo, 'joke');//모든 글 검색

  $jokes = [];
  foreach ($result as $joke) {
      //print_r($joke);

      $author = findById($pdo, 'author', 'id', $joke['authorId']); // author 테이블 id로 검색한 열 출력
      //print_r($author);
      $jokes[] = [
          'id' => $joke['id'],
          'joketext' => $joke['joketext'],
          'jokedate' => $joke['jokedate'],
          'name' => $author['name'],
          'email' => $author['email']
      ];
  }


  $title = '유머 글 목록';

  $totalJokes = total($pdo, 'joke');

  ob_start();

  include  __DIR__ . '/../templates/jokes.html.php';

  $output = ob_get_clean();

}
catch (PDOException $e) {
  $title = '오류가 발생했습니다';

  $output = '데이터베이스 오류: ' . $e->getMessage() . ', 위치: ' .
  $e->getFile() . ':' . $e->getLine();
}

include  __DIR__ . '/../templates/layout.html.php';
