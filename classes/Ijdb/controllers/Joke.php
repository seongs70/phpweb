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
    private $categoriesTable;
    private $authentication;

    public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorsTable,DatabaseTable $categoriesTable, Authentication $authentication)
    {
        $this->jokesTable = $jokesTable;
        $this->authorsTable = $authorsTable;
        $this->categoriesTable = $categoriesTable;
        $this->authentication = $authentication;
    }
    //글 목록페이지 메서드로 변환
    public function list(){
        if(isset($_GET['category'])){
            $category = $this->categoriesTable->findById($_GET['category']);
            $jokes = $category->getJokes();
        } else {
            $jokes = $this->jokesTable->findAll();
        }

		$title = '글 목록';

		$totalJokes = $this->jokesTable->total();

		$author = $this->authentication->getUser();
        // print_r($author);
		return ['template' => 'jokes.html.php',
				'title' => $title,
				'variables' => [
					'totalJokes' => $totalJokes,
					'jokes' => $jokes,
					'user' => $author,
                    'categories' => $this->categoriesTable->findAll()
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
        if($joke->authorId != $author->id && !$author->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)){
            return;
        }
        $this->jokesTable->delete($_POST['id']);

    	header('location: index.php?route=joke/list');
    }
    public function addJoke($joke){
        $joke['authorId'] = $this->id;
        return $this->jokesTable->save($joke);
    }
    //폼 표시
    public function saveEdit(){

        $author = $this->authentication->getUser();

        $joke = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();
        $jokeEntity = $author->addJoke($joke);

        $jokeEntity->clearCategories();

		foreach ($_POST['category'] as $categoryId) {
			$jokeEntity->addCategory($categoryId);
		}
        header('location: index.php?route=joke/list');
    }

    //폼 처리
    public function edit(){

        $author = $this->authentication->getUser();
        $categories = $this->categoriesTable->findAll();
        if(isset($_GET['id'])){
            $joke = $this->jokesTable->findById($_GET['id']);
        }
            $title = '글 수정';

            return ['template' => 'editjoke.html.php',
                'title' => $title,
                'variables' => [
                    'joke' => $joke ?? null,
                    'user' => $author,
                    'categories' => $categories,
                ]
            ];
    }
}
