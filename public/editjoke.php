<?php
//글 등록과 수정을 모두 처리하는 곳
//등록은 Insert 쿼리를 수정은 update 쿼리를 실행
//update가 출력하는 템플릿 파일은 ID를 숨겨진 input태그로 출력
//URL뒤에 GET변수로 ID가 전달되면 수정, 그렇지 않으면 등록 요청으로 구별 editjoke.php?id=12처럼 id에 12를 전달하면 데이터베이스에 12인 글을 불러와 수정페이지를 표시
include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

try {
		//등록 폼
		if (isset($_POST['joke'])) {
		//$_POST배열 원소를 일일히 복사하지 않아도 모든 원소를 $joke 배열에 자동으로 배치,  이전에는'id' => $_POST['jokeid'] 했다
		//하지만 이렇게 하면 전송버튼도 폼필드 중 하나라 $POST배열에 원소를 생성해서 INSERT쿼리가 다음과 같이 생성된다.

		//이코드는 $_POST['joke'] 배열을 그대로 $joke에 복사하고 폼 데이터에 없는 authorId, jokedate 값을 $joke 배열에 추가한다.
		// 이때 폼 필드명과 데이터베이스 칼럼명은 정확히 일치해야 한다. id폼 필드 name은 joke[jokeid] 대신 joke[id]로 쓴다. 만약 joke[jokeid]로 쓰면 쿼리가 생성될때 jokeid가 칼럼명으로 쓰여 오류가 발생한다.
		$joke = $_POST['joke'];

		// 안쓰지만 참고 //INSERT INTO `joke` (`joketext`, `jokedate`, `authorId`, `submit`) unset()문을 추가하면 $joke 배열의 submit 원소를 제거할 수 있다.
		// 안쓰지만 참고 //$joke = $_POST['joke']구문은 $joke배열에 유머 글 폼 필드 데이터를 복사한다 submit필드처럼 save()함수에 전달할 필요가 없는 폼필드는 자연스럽게 제외된다.
		// 안쓰지만 참고 //배열에서 submit 원소 제거
		// 안쓰지만 참고 //unset($joke['submit']);

		$joke['authorId'] = 1;
		$joke['jokedate'] = new DateTime();
		// return print_r($joke);
		save($pdo, 'joke', 'id', $joke);
		header('location: jokes.php');
	} else {
		//수정폼
		if(isset($_GET['id'])){
			//해당아이디에 열 가져오기
			$joke = findById($pdo, 'joke', 'id', $_GET['id']);
		}
		$title = '유머 글 수정';

		ob_start();

		include  __DIR__ . '/../templates/editjoke.html.php';

		$output = ob_get_clean();
	}
}
catch (PDOException $e) {
	$title = '오류가 발생했습니다';

	$output = '데이터베이스 오류: ' . $e->getMessage() . ', 위치: ' .
	$e->getFile() . ':' . $e->getLine();
}

include  __DIR__ . '/../templates/layout.html.php';
