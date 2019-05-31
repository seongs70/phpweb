<?php
//index.php는 다음과 같은 작업을 수행
// 1. $route에 맞는 컨트롤러 인스턴스를 생성하고 액션 메서드를 호출한다.
// 2. loadTemplate함수를 호출
//3. URL을 검사하고 소문자로 변환한 다음 리다이렉트한다
//4. 템플릿 변수를 설정하고 템플릿 파일을 인클루드 한다.


//index와 entrypoint를 조합하면 url경로와 일치하는 컨트롤러와 액션 메서드를 적절히 호출할 수있다.
//컨트롤러 클래스를 추가하는 방법도 쉽다. classes 디렉터리에 클래스 파일을 저장하고 callAction()에서 액션 메서드를 호출하면 끝이다.

try{
    include  __DIR__ . '/../classes/EntryPoint.php';
    //액션에 값을 대입 action=list처럼
    // $action = $_GET['action'] ?? 'home';
    // //컨트롤러에 값을 대입
    // $controllerName = $_GET['controller'] ?? 'joke';
    // //하나의 URL변수로 컨트롤러와 액션을 지정 controller 와 action 대신 route 변수 하나만 쓰는 코드다.
    // //route 변수가 설정되지 않으면 'joke/home' 을 지정
    $route = $_GET['route'] ?? 'joke/home';

    $entryPoint = new EntryPoint($route);
    $entryPoint->run();
} catch (PDOException $e){
    $title = '오류가 발생했습니다.';
    $output = '데이터베이스 오류: ' . $e->getMessage() . ', 위치: ' .
    $e->getFile() . ':' . $e->getLine();

    include  __DIR__ . '/../templates/layout.html.php';
}
