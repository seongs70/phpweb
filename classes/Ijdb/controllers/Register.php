<?php
namespace Ijdb\Controllers;
use \Hanbit\DatabaseTable;
//사용자 등록폼을 출력
class Register{
    private $authorsTable;
    public function __construct(DatabaseTable $authorsTable)
    {
        $this->authorsTable = $authorsTable;
    }

    public function registrationForm()
    {
        return ['template' => 'register.html.php',
                'title' => '사용자 등록'
            ];
    }

    public function success()
    {
        return ['template' => 'registersuccess.html.php',
                'title' => '등록 성공'
            ];
    }

    public function registerUser() //폼처리를 담당
    {
        $author = $_POST['author'];


        //name, email, password필드 값을 검사하고 빈 값일 때 $valid에 false를 할당
        //데이터는 처음부터 유효하다고 가정
        $valid = true;
        $errors = [];
        //하지만 항목이 빈 값이면 $valid에 false할당
        if(empty($author['name'])){
            $valid = false;
            $errors[] = '이름을 입력해야 합니다.';
        }
        if(empty($author['email'])){
            $valid = false;
            $errors[] = '이메일을 입력해야 합니다.';
        } else if (filter_var($author['email'],FILTER_VALIDATE_EMAIL) == false ) {
            $valid = false;
            $errors[] = '유효하지 않은 이메일 주소 입니다.';
        } else {
            //이메일 주소가 빈 값이 아니고 유효하다면
            //이메일 주소를 소문자로 변환
            $author['email'] = strtolower($author['email']);
            //$author['email']을 소문자로 검색
            if(count($this->authorsTable->find('email', $author['email'])) > 0 ){
                $valid = false;
                $errors[] = '이미 가입된 이메일 주소입니다.';
            }
        }
        if(empty($author['password'])){
            $valid = false;
            $errors[] = '비밀번호를 입력해야 합니다.';
        }
        //$valid가 true라면 빈 항목이 없으므로 데이터를 추가 할 수 있음
        if($valid == true){
            //데이터베이스에 저장하기 전에 비밀번호를 해시화
            $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);
            //폼이 전송되면 $author변수는 소문자 메일과 비밀번호 해시값을 포함
            $this->authorsTable->save($author);

            header('Location: index.php?route=author/success');
        } else {
            //데이터가 유효하지 않으면 폼을 다시출력
            //$errors[]에 값을 할당하면 $erros배열의 마지막 원소로 추가된다. 빈 값을 확인할떄마다 오류메시지가 차례로 배열에 추가되며 이 메시지들을 템플릿을 사용해 사용자에게 안내한다
            //출력한 변수를 배열로 묶어 variable키에 할당
            return ['template' => 'register.html.php',
                    'title' => '사용자 등록',
                    'variables' => [
                        'errors' => $errors,
                        'author' => $author
                    ]
                ];
        }
    }

    public function list() {
        $authors = $this->authorsTable->findAll();

        return [
            'template' => 'authorlist.html.php',
            'title' => '사용자 목록',
            'variables' => [
                'authors' => $authors
            ]
        ];
    }
    public function permissions() {
        $author = $this->authorsTable->findById($_GET['id']);

        $reflected = new \ReflectionClass('\Ijdb\Entity\Author');
        $constants = $reflected->getConstants();

        return [
            'title' => '권한 수정',
            'template' => 'permissions.html.php',
            'variables' => [
                'author' => $author,
                'permissions' => $constants
            ]
        ];
    }

    public function savePermissions() {
        $author = [
            'id' => $_GET['id'],
            'permissions' => array_sum($_POST['permissions'] ?? [])
        ];
        $this->authorsTable->save($author);

        header('Location: index.php?route=author/list');
    }
}
