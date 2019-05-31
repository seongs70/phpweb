<?php
//컨트롤러 코드를 모두 JokeController로 통합, 모든 페이지를 한 파일에서 표시할 수 있다.

class JokeController {
    private $authorsTable;
    private $jokesTable;

    public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorsTable)
    {
        $this->jokesTable = $jokesTable;
        $this->authorsTable = $authorsTable;
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
                'email' => $author['email']
            ];
        }

        $title = '유머 글 목록';

        $totalJokes = $this->jokesTable->total();

        return ['template' => 'jokes.html.php',
        'title' => $title,
        'variables' => [
            'totalJokes' => $totalJokes,
            'jokes' => $jokes
        ]
    ];
    }
    public function home() {
        $title = 'phpweb';

        return ['template' => 'home.html.php', 'title' => $title];
    }
    //delete 페이지 메서드로 변환
    public function delete(){
        $this->jokesTable->delete($_POST['id']);

    	header('location: index.php?route=joke/list');
    }
    public function edit(){
        if (isset($_POST['joke'])) {
            $joke = $_POST['joke'];
            $joke['authorId'] = 1;
            $joke['jokedate'] = new DateTime();
            // return print_r($joke);
            $this->jokesTable->save($joke);
            header('location: index.php?route=joke/list');
        } else {
            //수정폼
            if(isset($_GET['id'])){
                //해당아이디에 열 가져오기
                $joke = $this->jokesTable->findById($_GET['id']);
            }
            $title = '유머 글 수정';

            return ['template' => 'editjoke.html.php',
                'title' => $title,
                'variables' => [
                    'joke' => $joke ?? null
                ]
            ];
        }
    }
}
