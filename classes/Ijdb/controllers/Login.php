<?php
namespace Ijdb\Controllers;

class Login
{
    private $authentication;

    public function __construct(\Hanbit\Authentication $authentication)
    {

        $this->authentication = $authentication;
    }
    public function error()
    {
        return ['template' => 'loginerror.html.php',
                'title' => '로그인되지 않았습니다.'
            ];
    }

    public function loginForm()
    {
        return ['template' => 'login.html.php',
                'title' => '로그인'
            ];
    }
    //로그인()메서드를 호출하고 사용자 입력 데이터를 전달한다.
    //login()메서드가 참이면 로그인을 수행하고 메시지 페이지로 이동
    public function processLogin()
    {
        if($this->authentication->login($_POST['email'], $_POST['password'])){
            header('location: index.php?route=login/success');
        } else {
            return ['template' => 'login.html.php',
                    'title' => '로그인',
                    'variables' => [
                        'error' => '사용자 이름/비밀번호가 유효하지 않습니다.'
                    ]
                ];
        }
    }

    public function success()
    {
        return ['template' => 'loginsuccess.html.php',
                'title' => '로그인 성공'
            ];
    }

    public function logout()
    {

        $_SESSION = [];
        $_SESSION = Array();
        unset($_SESSION);
        return ['template' => 'logout.html.php',
                'title' => '로그아웃되었습니다.'
        ];
    }
}
