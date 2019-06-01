<?php
namespace Hanbit;
//이클래스는 URL 경로를 다룬다.
// 리다이렉트에 문제가 생기면 checkURL(), 템플릿은 loadTemplate(), 특정 URL에 접속할 수 없으면 callAction()

//프레임워크 코드와 프로젝트 전용 코드를 분리해 모든 프로젝트에서 사용하는 하나의 EntryPoint 클래스
//EntryPoint 클래스는 생성자로 액션 클래스를 전달한다.
//쇼밍몰 웹사이트는 다음고 ㅏ같이 클래스 객체를 생성한다. $entryPoint = new EntryPoint($route, new ShopActions());
class EntryPoint
{
    private $route;
    private $method;
    private $routes;
                                                                //인터페이스를 사용한 타입힌트
    public function __construct(string $route, string $method, \Hanbit\Routes $routes)
    {
        $this->route = $route;
        //$routes는 IjdbRoutes인스턴스를 담을 변수다.
        $this->routes = $routes;
        $this->method = $method;
        $this->checkUrl();
    }
    private function checkUrl() {
        if($this->route !== strtolower($this->route)){
            http_response_code(301);
            return print($this->route);
            header('location: index.php?route='.strtolower($this->route));
        }
    }

    private function loadTemplate($templateFileName, $variables =[])
    {
        extract($variables);
        ob_start();
        include __DIR__ . '/../../templates/'.$templateFileName;

        return ob_get_clean();
    }

    //템플릿 기능을 담당
    public function run(){
        $routes = $this->routes->getRoutes();

        $controller = $routes[$this->route][$this->method]['controller'];
        $action = $routes[$this->route][$this->method]['action'];

        $page = $controller->$action();

        $title = $page['title'];

        if(isset($page['variables'])){
            $output = $this->loadTemplate($page['template'], $page['variables']);
        } else {
            $output = $this->loadTemplate($page['template']);
        }
        include  __DIR__ . '/../../templates/layout.html.php';
    }
}
