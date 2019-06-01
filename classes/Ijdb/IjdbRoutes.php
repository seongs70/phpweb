<?php
//URL을 이용해 액션을 결정하는 기능을 '라우터라'부른다.
//라우터는 IjdbRoutes클래스가 담당하며 단순한 조건문을 나열해 URL과 컨트롤러 액션을 연결한다.
namespace Ijdb;
//인터페이스 상속
class IjdbRoutes implements \Hanbit\Routes
{
    //URL경로를 검사하는 if문을 그대로 가져온 callAction()
    //컨트롤러 액션 메서드를 호출하고 $page변수를 반환하는 역할을 맡는다.
    public function getRoutes()
    {
        //DB연동 클래스 파일이 아니므로 수동으로 인클루드
        include __DIR__ . '/../../includes/DatabaseConnection.php';
        //여기도 오토로드 먹는다
        //타입 힌트로 클래스명을 가리키거나 new키워드를 객체를 생성할 때 , php는 현재 네임스페이스에서 해당 클래스를 찾는다.
        //PDO객체를 사용하려면 맨 앞에 역슬래시를 붙여 \PDO로 써야 정확히 해당 클래스를 가리 킬수 있다.
        //DateTime이나 PDOException도 \DateTime과 \PDOException으로 고쳐야한다.
        //역슬래시 접두어 없이 PDO쓰면 원래의 PDO대신 \Habbit\PDO를 불러온다.
        //PDO클래스가 속한 영역, 네임스페이스가 지정되지 않은 최상위 영역을 전역 네임스페이스라 한다.  전역 네임스페이스의 클래스를 참조할 땐 맨앞에 역슬래시를 붙여야한다.
        $jokesTable = new \Hanbit\DatabaseTable($pdo, 'joke', 'id');
        $authorsTable = new \Hanbit\DatabaseTable($pdo, 'author', 'id');
        $jokeController = new \Ijdb\Controllers\Joke($jokesTable, $authorsTable);
        $routes = [
            'joke/edit' =>[
                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'saveEdit'
                ],
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'edit'
                ]
            ],
            'joke/delete' =>[
                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'delete'
                ]
            ],
            'joke/list' =>[
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'list'
                ]
            ],
            'joke/home' => [
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'home'
                ]
            ],
        ];

        return $routes;
    }
}