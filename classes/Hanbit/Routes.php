<?php
// $routes = $this-routes->getRoutes();
// public function __construct(string $route, string $method, \Ijdb\IjdbRoutes $route){}
// IjdbRoutes 객체를 세번째 인수로 전달하지 않으면 EntryPoint 클래스 객체를 생성할 수 없다.
// getRoutes()메서드가 확실히 실행되도록 보장한대신 유연성을 포기해야한다 가령 쇼핑몰 사이트를 새로 만들면 \Ijdb\IjdbRoutes 대신 \Shop\Routes 클래스를 타입힌트로 지정해야 한다.
// 모든 웹사이트에 쓸수 있는 유연성과, 필요한 타입을 지정하는 안정성은 인터페이스다
// 인터페이스는 클래스 메서드를 묘사하지만 실제 로직을 담지 않는 언어 구조다. 클래스는 인터페이스를 상속받는다.

namespace Hanbit;
interface Routes
{
    public function getRoutes();
}
//이제 EntryPoint생성자에 인터페이스로 타입힌트를 지정한다.
// public function __construct(string $route, string $method, \Hanbit\Routes $routes){}

// 인터페이스를 도입하고 두가지 효과
// 1.IjdbRoutes 클래스는 인터페이스에 정의된 메서드를 구현해햐 한다.
// 2. 인터페이스를 타입힌트로 지정하면 인터페이스를 상속받은 IjdbRoutes 클래스를 인수로 전달할 수 있다.
//
// 이제 다음과 같이 쇼핑몰에서 \Hanbit\Routes 인터페이스를 상속받아 \Shop\Routes 클래스를 만들면 EntryPoint 클래스 생성자 인수로 전달할 수 있다.
// namespace Shop;
// class Routes implements \Hanbit\Routes {
//     public function getRoutes() {
//         //쇼핑몰의 경로를 반환
//     }
// }
