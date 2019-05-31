<?php
//페이지를 새로 추가하려면 두가지 작업을 해야한다.
//1. JokeController에 메서드를 추가한다
//2. else if 영역을 추가한다.
//$function = 'edit';
//$jokeController->$function(); 이코드는 $function에 edit를 할당하며 $jokeController->edit()를 호출한다.
//이 기닝을 이용해 GET변수명과 일치하는 메서드를 즉시 호출 할 수 있다.
function loadTemplate($templateFileName, $variables =[])
{
    //만약 $page['variables']배열에 page와 title키가 존재한다면 $title과 $page변수가 다른값으로 교체된다.
    //템플릿을 불러오는 코드를 별도 함수로 분리해야한다.
    //variable 배열에 page나 title키가 있어도 기존 변수를 더어쓸 염려가 없다.
    //이 함수에서 extract를 실행하고 템플릿을 불러오기 때문이다.
    extract($variables);
    ob_start();
    include __DIR__ . '/../templates/'.$templateFileName;

    return ob_get_clean();
}

try{
    //DB연동
    include __DIR__ . '/../includes/DatabaseConnection.php';
    //범용함수
    include __DIR__ . '/../classes/DatabaseTable.php';
    //Joke페이지 모두 모은 함수
    include __DIR__ . '/../controllers/JokeController.php';

    $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
    $authorsTable = new DatabaseTable($pdo, 'author', 'id');
    $jokeController = new JokeController($jokesTable, $authorsTable);
    $action = $_GET['action'] ?? 'home';
    $page = $jokeController->$action();
    $title = $page['title'];
    //extract로 변수를 추출하면 배열의 모든 키가 변수명으로 생성되고 키에 대응하는 값이 각변수에 할당된다.
    //$page['variables'] 배열을 extract로 추출하면 템플릿에서 쓰는 변수를 다음과 같이 한번에 생성할 수 있다.
    if(isset($page['variables'])){
        $output = loadTemplate($page['template'], $page['variables']);
    } else {
        $output = loadTemplate($page['template']);
    };
    //모든 메서드에서 반보적으로 실행되는 include문은 index에 두면 한번만 써도 된다.
    //대신 home.html.php 같은 인클루드 파일명은 액션에서 지정해 index.php 로 전달해야 한다.
    //이제 index파일에서 $page 배열의 template 원소를 읽는다 컨트롤러 액션은 $output 변수를 반환하지 않는 대신 index에서 인클루드할 파일명을 template 키에 담아 반환해야한다.
} catch (PDOException $e){
    $title = '오류가 발생했습니다.';

    $output = '데이터베이스 오류: ' . $e->getMessage() . ', 위치: ' .
    $e->getFile() . ':' . $e->getLine();
}

include  __DIR__ . '/../templates/layout.html.php';
