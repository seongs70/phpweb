<?php

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

//print_r(insertJoke($pdo, ['authorId' => 1, 'jokeText' => '도레', 'jokedate' => new DateTime()]));

// print_r( allAuthors($pdo));

// insert($pdo, 'joke' ,['authorId' => 1, 'jokeText' => '미레', 'jokedate' => new DateTime()]);
//update($pdo, 'joke', 'id', ['id'=>37, 'authorId' => 1, 'jokeText' => '레레', 'jokedate' => new DateTime()]);
// //데이터베이스에서 모든 글 가져오기
// $allJokes = findAll($pdo, 'joke');
//
// //데이터베이스에서 모든 작성자 가져오기
// $allAuthors = findAll($pdo, 'author');
// // print_r($allAuthors);

//book 테이블에서 isbn 칼럼값이 29인 레코드 삭제
// delete($pdo, 'book', 'isbn' , 29);

//아이디가 3인 열검색
//findById($pdo, 'joke', 'id', 3);


//전체 글 개수 확인
// print_r(total($pdo, 'joke'));

//글과 작성자 적보를 함께 가져오는 범용함수
//목록 데이터를 가져올때 findAll()과 findByID()함수를 단계적으로 호출한다
//조인문과 같다. Author테이블과 Joke테이블 데이터 다 출력
$result = findAll($pdo, 'joke');//모든 글 검색

$jokes = [];
foreach ($result as $joke) {
    //print_r($joke);

    $author = findById($pdo, 'author', 'id', $joke['authorId']); // author 테이블 id로 검색한 열 출력
    //print_r($author);
    $jokes[] = [
        'id' => $joke['id'],
        'joketext' => $joke['joketext'],
        'name' => $author['name'],
        'email' => $author['email']
    ];
}
