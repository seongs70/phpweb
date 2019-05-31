<?php
//클래스의 제약 조건부여
//1. $pdo 인스턴스를 전달하지 않으면 DatabaseTable 클래스 인스턴스를 생성할 수 없다.
//2. 첫번째 인수는 유효한 PDO 인스턴스 3. 객체가 생성된후 $pdo 변경불가
class DatabaseTable{
	//$jokesTable->pdo = '문자열'; 이런경우를 대비해서 private
	private $pdo;
	private $table;
	private $primaryKey;

	//클래스 생성자 정의에 인수가 있고 기본강ㅄ이 없으면, 인스턴스를 생성할 때 반드시
	//인수를 전달해야 한다. 그렇지 않으면 오류가 발생한다.
	//메서드를 호출하기 전에 변수가 설정되지 않거나 $pdo 변수에 문자열이 지정되거나 이런 오류를 없애기 위해 생성자 사용한다.
	//__construct($pdo, $table, $primaryKey)에 있는 $pdo는 함수 인수. 함수인수는 해당 함수 안에서만 쓸 수 있고 다른 함수에서 접근할 수 없다
	//생성자 안에서 클래스 변수에 값을 할당해야 한다.
	//생성자 인수 정의에 따라 DatabaseTable 인스턴스를 만들때 다음과 같이 3가지 인수를 제공해야한다
	//$jokesTable = new DatabaseTable($pdo, 'joke', 'id');
	public function __construct(PDO $pdo, string $table, string $primaryKey)
	{
		$this->pdo = $pdo;
		$this->table = $table;
		$this->primaryKey = $primaryKey;

	}
	//쿼리 실행
	private function query($sql, $parameters = []) {
		//데이터베이스 커넥션과 테이블명을 인수로 전달받지 않고 클래스 변수에서 가져온다
		$query = $this->pdo->prepare($sql);
		$query->execute($parameters);
		return $query;
	}

	// 함수 코드 중 테이블명을 변수로 대체하면 변수명에 따라 테이블을 조회하는 함수로 변신한다
	// 테이블명을 인수로 받는 함수를 하나 만들면 테이블마다 전용함수를 만들 필요가 없다.
	public function findAll()
	{
		$result = $this->query('SELECT * FROM `' . $this->table . '`');

		return $result->fetchAll();
	}
	//PK로 열검색
	public function findById($value) {
		$query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :value';
		// query() 함수에서 사용할 $parameters 배열 생성
		$parameters = ['value' => $value];

		// query() 함수에서 사용할 $parameters 배열 제공
		$query = $this->query($query, $parameters);

		return $query->fetch();
		// print_r($query->fetch());
	}


	private function insert($fields) {
		$keys = [];
		// $fields = ['authorId' => 1, 'jokeText' => '도레미파', 'jokedate' => new DateTime()]
		foreach ($fields as $key => $value) {
			$keys[] = '`' . $key . '`';
		}
		$keys = implode(', ', $keys); // (`authorId`, `jokeText`, `jokedate`)
		$query = 'INSERT INTO `' . $this->table .'` ('.$keys.') '; //INSERT INTO `joke` (`authorId`, `jokeText`, `jokedate`)
		$query .= 'VALUES (';
		$fieldKeys = array_keys($fields); //Array ( [0] => authorId [1] => jokeText [2] => jokedate )

		$query .= ':' . implode(', :', $fieldKeys) . ')'; //INSERT INTO `joke` (`authorId`, `jokeText`, `jokedate`) VALUES (:authorId, :jokeText, :jokedate)
		$fields = $this->processDates($fields); //Array ( [authorId] => 1 [jokeText] => 도레 [jokedate] => 2019-05-29 09:01:50 )
		$this->query($query, $fields);//query($pdo, INSERT INTO `joke` (`authorId`, `jokeText`, `jokedate`) VALUES (:authorId, :jokeText, :jokedate), Array ( [authorId] => 1 [jokeText] => 도레 [jokedate] => 2019-05-29 09:03:28 ))
	}


	private function update($fields) {

		$query = ' UPDATE `'. $this->table .'` SET ';


		foreach ($fields as $key => $value) {
			$query .= '`' . $key . '` = :' . $key . ',';
		}

		$query = rtrim($query, ',');

		$query .= ' WHERE `' . $this->primaryKey .'` = :primaryKey';


		// :primaryKey 변수 설정
		//print_r($fields);
		$fields['primaryKey'] = $fields['id'];

		$fields = $this->processDates($fields);

		$this->query($query, $fields);
	}

	// 전체글 개수를 확인
	public function total() {
		$query = $this->query('SELECT COUNT(*) FROM `' . $this->table . '`');
		$row = $query->fetch();

		return $row[0];
	}


	//삭제
	public function delete($id){
		$parameters = [':id' => $id];
		//테이블 기본키 칼럼이 무조건 id라고 간주하지않는 테이블에는 쓸수 없다
		//테이블 칼럼 구조와 무관하게 작동하려면 id로 고정된 기본 키를 변수로 대체해야 한다.
		$this->query('DELETE FROM `' . $this->table .'` WHERE `' . $this->primaryKey . '` = :id', $parameters);
	}


	//날짜 형식 처리
	public function processDates($fields) {
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
	public function save($record)
	{
		try{
			if($record[$this->primaryKey] == ''){
				$record[$this->primaryKey] = null;
			}
			$this->insert($record);
		}
		catch (PDOException $e){
			$this->update($record);
		}
	}

}
