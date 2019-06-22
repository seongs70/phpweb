<?php
//컨트롤러 코드를 모두 JokeController로 통합, 모든 페이지를 한 파일에서 표시할 수 있다.
namespace Ijdb\Controllers;
//네임스페이스를 정확히 지정하지 않으면 PHP는 현재 네임스페이스에서 클래스를 찾는다. 컨트롤러는 Ijdb 네임스페이스에 있으므로
//DatabaseTable클래스를 불러오면 Ijdb\DatabaseTable을 찾는다. 클래스명에 앞에 네임스페이스를 정확히 써야 클래스를 불러오므르 DatabaseTable을 \Hanbit\DatabaseTable로 고쳐야한다.
//다른방법은 네임스페이스를 선언한다음 use키워드 뒤에 정규화된 클래스명을 쓴다.
use \Hanbit\DatabaseTable;
use \Hanbit\Authentication;
class Joke {
    private $authorsTable;
    private $jokesTable;

    public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorsTable, Authentication $authentication)
    {
        $this->jokesTable = $jokesTable;
        $this->authorsTable = $authorsTable;
        $this->authentication = $authentication;
    }
    //글 목록페이지 메서드로 변환
    public function list(){
        $result = $this->jokesTable->findAll();
        $jokes = [];
        foreach ($result as $joke) {
            //print_r($joke);

            $author = $this->authorsTable->findById($joke['authorId']); // author 테이블 id로 검색한 열 출력
            //print_r($author);
            $jokes[] = [
                'id' => $joke['id'],
                'joketext' => $joke['joketext'],
                'jokedate' => $joke['jokedate'],
                'name' => $author['name'],
                'email' => $author['email'],
                'authorId' => $author['id'],
            ];
        }

        $title = '글 목록';

        $totalJokes = $this->jokesTable->total();

        return ['template' => 'jokes.html.php',
        'title' => $title,
        'variables' => [
            'totalJokes' => $totalJokes,
            'jokes' => $jokes,
            'userId' => $author['id'] ?? null
        ]
    ];
    }
    public function home() {
        $title = 'phpweb';

        return ['template' => 'home.html.php', 'title' => $title];
    }
    //delete 페이지 메서드로 변환
    public function delete(){
        $author = $this->authentication->getUser();
        $joke = $this->jokesTable->findById($_POST['id']);
        if($joke['authorId'] != $author['id']){
            return;
        }
        $this->jokesTable->delete($_POST['id']);
    	header('location: index.php?route=joke/list');
    }
    //폼 표시
    public function saveEdit(){
            $author = $this->authentication->getUser();
            //가령 폼파일을 정교하게 모방해 다른 웹사이트로 데이터 전송이 가능할 수 있다. <form action="http://192.168.10.10/joke/edit?id=1">
            //이폼은 로그인 사용자와 관계없이 누구나 값을 입력하고 제출할 수 있다.그결과 데이터가 바뀐다 폼데이터를 처리하기 전에 사용자 검사 기능을 추가해야한다
            //joke 테이블 authorId칼럼값이 로그인 사용자의 ID와 다르면 return명령어를 실행
            if(isset($_GET['id'])){
                $joke = $this->jokesTable->findById($_GET['id']);
                if ($joke['authorId'] != $author['id']){
                    return;
                }
            }

            $joke = $_POST['joke'];
            $joke['authorId'] = $author['id'];
            $joke['jokedate'] = new \DateTime();
            $this->jokesTable->save($joke);
            header('location: index.php?route=joke/list');
    }
    //폼 처리
    public function edit(){
        $author = $this->authentication->getUser();
        if(isset($_GET['id'])){
            $joke = $this->jokesTable->findById($_GET['id']);
        }
            $title = '글 수정';

            return ['template' => 'editjoke.html.php',
                'title' => $title,
                'variables' => [
                    'joke' => $joke ?? null,
                    'userId' => $author['id'] ?? null
                ]
            ];
    }
}
