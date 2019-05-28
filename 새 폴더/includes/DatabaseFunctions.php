<?php
//getjoke()와 updatejoke()함수는 데이터 처리를 담당한다.
function query($pdo, $sql, $parameter = []) {
    $query = $pdo->prepare($sql);
    $query->execute($parameter);

    return $query;
}
//query()함수는 :id만 처리할 수 있어서 ㄴ=매개변수에 따라 유동적으로 쿼리를 처리할 수 있도록
//바인딩할 매개변수를 배열로 만들어 query()함수에 전달
function getJoke($pdo, $id) {

	// query() 함수에서 사용할 $parameters 배열 생성
	$parameters = [':id' => $id];


	// query() 함수에서 사용할 $parameters 배열 제공
	$query = query($pdo, 'SELECT * FROM `joke` WHERE `id` = :id', $parameters);

	return $query->fetch();
}
function totalJokes($pdo){
    // query()함수로 보낼 빈 배열 생성

    //query() 함수를 호출할 때 빈 $parameter배열 전달
    $query = query($pdo, 'SELECT COUNT(*) FROM `joke`');
    $row = $query->fetch();
    return $row[0];
}
function insertJoke($pdo, $fields) {
    $query = 'INSERT INTO `joke` (';
    foreach ($fields as $key => $value){
        $query .= '`' . $key . '`,';
    }

    $query = rtrim($query, ',');

    $query .= ') VALUES (';

    foreach($fields as $key => $value){
        $query .= ':' . $key .',';
    }

    $query = rtirm($query, ',');

    $query .= ')';

    query($pdo, $query, $fields);
}
function updateJoke($pdo, $fields){
    $query = ' UPDATE `joke` SET ';

    foreach($fields as $key => $value){
        $query .= '`' . $key . '` = :' . $key . ',';
    }

    $query = rtrim($query, ',');

    $query .= ' WHERE `id` = :primaryKey';

    //:primaryKey 변수 설정
    $fields['primaryKey'] = $fields['id'];

    query($pdo, $query, $fields);

    // :primaryKey 변수 설정
    $fields['primaryKey'] = $fields['id'];
}
function deleteJoke($pdo, $id){
    $parameters=[':id'=>$id];

    query($pdo, 'delete from `joke` where `id` = :id', $parameters);
}
function allJokes($pdo){
    $jokes = query($pdo, 'select `joke`.`id`, `joketext`, `name`, `email` from `joke` inner join `author` on `authorid` = `author`.`id`');
    return $jokes->fetchAll();
}
