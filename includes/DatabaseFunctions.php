<?php

function query($pdo, $sql, $parameters = []) {
	$query = $pdo->prepare($sql);
	$query;
	$query->execute($parameters);
	return $query;
}


function totalJokes($pdo) {
  $query = query($pdo, 'SELECT COUNT(*) FROM `joke`');
  $row = $query->fetch();
  return $row[0];
}


//PK로 열검색
function findById($pdo, $table, $primaryKey, $value) {
	$query = 'SELECT * FROM `' . $table . '` WHERE `' . $primaryKey . '` = :value';
	// query() 함수에서 사용할 $parameters 배열 생성
	$parameters = ['value' => $value];

	// query() 함수에서 사용할 $parameters 배열 제공
	$query = query($pdo, $query, $parameters);

	return $query->fetch();
	// print_r($query->fetch());
}


function insert($pdo, $table, $fields) {
	$keys = [];
	// $fields = ['authorId' => 1, 'jokeText' => '도레미파', 'jokedate' => new DateTime()]
	foreach ($fields as $key => $value) {
		$keys[] = '`' . $key . '`';
	}
	$keys = implode(', ', $keys); // (`authorId`, `jokeText`, `jokedate`)
	$query = 'INSERT INTO `' . $table .'` ('.$keys.') '; //INSERT INTO `joke` (`authorId`, `jokeText`, `jokedate`)
	$query .= 'VALUES (';
	$fieldKeys = array_keys($fields); //Array ( [0] => authorId [1] => jokeText [2] => jokedate )

	$query .= ':' . implode(', :', $fieldKeys) . ')'; //INSERT INTO `joke` (`authorId`, `jokeText`, `jokedate`) VALUES (:authorId, :jokeText, :jokedate)
	$fields = processDates($fields); //Array ( [authorId] => 1 [jokeText] => 도레 [jokedate] => 2019-05-29 09:01:50 )
	query($pdo, $query, $fields);//query($pdo, INSERT INTO `joke` (`authorId`, `jokeText`, `jokedate`) VALUES (:authorId, :jokeText, :jokedate), Array ( [authorId] => 1 [jokeText] => 도레 [jokedate] => 2019-05-29 09:03:28 ))
}


function update($pdo, $table, $primaryKey, $fields) {

	$query = ' UPDATE `'. $table .'` SET ';


	foreach ($fields as $key => $value) {
		$query .= '`' . $key . '` = :' . $key . ',';
	}

	$query = rtrim($query, ',');

	$query .= ' WHERE `' . $primaryKey .'` = :primaryKey';


	// :primaryKey 변수 설정
	//print_r($fields);
	$fields['primaryKey'] = $fields['id'];

	$fields = processDates($fields);

	query($pdo, $query, $fields);
}

// 전체글 개수를 확인
function total($pdo, $table) {
	$query = query($pdo, 'SELECT COUNT(*) FROM `' . $table . '`');
	$row = $query->fetch();

	return $row[0];
}


//삭제
function delete($pdo, $table, $primaryKey, $id){
	$parameters = [':id' => $id];
	//테이블 기본키 칼럼이 무조건 id라고 간주하지않는 테이블에는 쓸수 없다
	//테이블 칼럼 구조와 무관하게 작동하려면 id로 고정된 기본 키를 변수로 대체해야 한다.
	query($pdo, 'DELETE FROM `' . $table .'` WHERE `' . $primaryKey . '` = :id', $parameters);
}

// 함수 코드 중 테이블명을 변수로 대체하면 변수명에 따라 테이블을 조회하는 함수로 변신한다
// 테이블명을 인수로 받는 함수를 하나 만들면 테이블마다 전용함수를 만들 필요가 없다.
function findAll($pdo, $table)
{
	$result = query($pdo, 'SELECT * FROM `' . $table . '`');

	return $result->fetchAll();
}

//function findById();
function processDates($fields) {
	foreach ($fields as $key => $value) {
		if ($value instanceof DateTime) {
			$fields[$key] = $value->format('Y-m-d H:i:s');
		}
	}

	return $fields;
}

//GET데이터에 따라 등록을 할지 수정을 할지 구분하는 범용함수
//$record[$primaryKey] == ''조건문은 ISERT쿼리를 실행할 때 id칼럼에 빈문자열이 들어가지 않도록 예방한다.
//if else 대신 try와 catch를 쓰는 이유는 등록 쿼리를 실행 했을때 실패하면 수정 쿼리를 실행 하기 위해서 이다. 지정한 id글이 있으면 중복키 오류가 발생하고 update() 함수가 대신 실행된다
function save($pdo, $table, $primaryKey, $record)
{
	try{
		if($record[$primaryKey] == ''){
			$record[$primaryKey] = null;
		}
		insert($pdo, $table, $record);
	}
	catch (PDOException $e){
		update($pdo, $table, $primaryKey, $record);
	}
}
