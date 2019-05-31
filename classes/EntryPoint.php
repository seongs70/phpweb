<?php
//이클래스는 URL 경로를 다룬다.
class EntryPoint
{
    private $route;

    public function __construct($route)
    {
        $this->route = $route;
        $this->checkUrl();
    }
    private function checkUrl() {
        if($this->route !== strtolower($this->route)){
            http_response_code(301);
            header('location'.strtolower($this->route));
        }
    }



    //URL경로를 검사하는 if문을 그대로 가져온 callAction()
    //컨트롤러 액션 메서드를 호출하고 $page변수를 반환하는 역할을 맡는다.
    private function  callAction(){
        //DB연동
        include __DIR__ . '/../includes/DatabaseConnection.php';
        //범용함수
        include __DIR__ . '/../classes/DatabaseTable.php';

        $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
        $authorsTable = new DatabaseTable($pdo, 'author', 'id');
        if($this->route === 'joke/list'){
            include __DIR__.'/../controllers/JokeController.php';
            $controller = new JokeController($jokesTable, $authorsTable);
            $page = $controller->list();
        }
        else if($this->route === 'joke/home'){
            include __DIR__.'/../controllers/JokeController.php';
            $controller = new JokeController($jokesTable, $authorsTable);
            $page = $controller->home();
        }
        else if($this->route === 'joke/edit'){
            include __DIR__.'/../controllers/JokeController.php';
            $controller = new JokeController($jokesTable, $authorsTable);
            $page = $controller->edit();
        }
        else if($this->route === 'joke/delete'){
            include __DIR__.'/../controllers/JokeController.php';
            $controller = new JokeController($jokesTable, $authorsTable);
            $page = $controller->delete();
        }
        else if($this->route === 'register'){
            include __DIR__.'/../controllers/RegisterController.php';
            $controller = new RegisterController($authorsTable);
            $page = $controller->showForm();
        }

        return $page;
    }
    
    private function loadTemplate($templateFileName, $variables =[])
    {
        extract($variables);
        ob_start();
        include __DIR__ . '/../templates/'.$templateFileName;

        return ob_get_clean();
    }

    //템플릿 기능을 담당
    public function run(){
        $page = $this->callAction();

        $title = $page['title'];

        if(isset($page['variables'])){
            $output = $this->loadTemplate($page['template'], $page['variables']);
        } else {
            $output = $this->loadTemplate($page['template']);
        }
        include  __DIR__ . '/../templates/layout.html.php';
    }
}
