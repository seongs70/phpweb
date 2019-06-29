<?php
//클래스를 자동으로 로드 하는 코드 입니다. 클래스를 사용하는 파일에서 이 파일을 include 하면 됩니다.
//spl_autoload_register() 함수의 인자로 클래스 이름을 받아서 로드하는 익명 함수를 제공합니다.
//이제 PHP 에서도 클래스를 사용해서 객체지향 프로그래밍을 하는 것이 일반적이 되어 가고 있습니다.
//보통 클래스는 하나의 파일에 정의하고 클래스를 사용할때 include 해서 사용하게 됩니다.
//이 때 클래스가 많아지면 include 하는 코드가 많아지게 됩니다.
//PHP 5에서 부터는 이러한 클래스를 자동으로 로드하는 기능을 제공합니다. spl_autoload_register() 함수를 사용하여 처리할 수 있습니다.

spl_autoload_register(function($className) {
    include __DIR__ . '/../classes/' . $className . '.php';
    // echo '/../classes/' . $className . '.php';
    // echo '<br>';
    // echo $className;
    // echo '<br>';
});
