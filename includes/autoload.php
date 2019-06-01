<?php
//네임스페이스 소속 클래스에서 오토로더가 발동되면 네임스페이스가 포함된 전체 클래스명이 오토러더 함수로 전달된다.
//ex로 EntryPoint를 불러올때 오토로더가 전달받는 인수는 Hanbit\EntryPoint
function autoloader($className){
    //Hanbit\EntryPoint가 Hanbit/EntryPoint.php로 변환되어 파일시스템에 맞는 경로가 완성된다.
    $fileName = str_replace('\\','/',$className).'.php';

    $file = __DIR__.'/../classes/'.$fileName;
    include $file;

}

spl_autoload_register('autoloader');
