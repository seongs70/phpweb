<?php
namespace Hanbit;

class Authentication
{
    private $users;
    private $usernameColumn;
    private $passwordColumn;
    //칼럼명을 직접 쓰지 않고 생성자로 전달하면 클래스의 범용성이 향상된다. 칼럼명이 email, password가 아니더라도 모든 웹사이트에서 이 클래스를 사용할 수 있다.
    //생성자에서 세션을 시작하므로 session_start()를 직접 호출 필요가 없고, Authentication 인스턴스를 생성한 페이지는 세션도 함께 제어한다.

    public function __construct(DatabaseTable $users, $usernameColumn, $passwordColumn)
    {
        session_start();
        $this->users = $users; //사용자 계정 테이블을 처리할 DatabaseTable 인스턴스
        $this->usernameColumn = $usernameColumn;
        $this->passwordColumn = $passwordColumn; // 로그인 비밀번호가 저장된 칼럼
    }

    public function login($username, $password)
    {
        $user = $this->users->find($this->usernameColumn, strtolower($username));

        //세션 데이터와 데이터베이스를 비교하는 코드
        //1. 세션에 저장된 메일 주소로 데이터베이스에서 사용자를 검색, 로그인 폼에 입력했던 메일 주소
        //2. 데이터베이스 해당 메일 주소 레코드가 존재하는지 확인, 사용자가 입력한 메일 주소가 틀렸을 가능성에 대비
        //3. 데이터베이스에 저장된 비밀번호와 세션에 저장된 비밀번호를 비교, 로그인 이후 비밀번호가 변경됬을 때 사용자를 로그아웃
        //// 필요한것 1. 사용자가 입력한 메일 주소와 비밀번호를 검사하고 로그인 하는 메서드, 로그인 폼 제출 시 호출
        //// 필요한것 2. 현재 사용자가 로그인 상태인지, 로그인 후 비밀번호가 변경됐는지 확인하는 메서드, 접근 권한이 설정된 모든페이지에서 호출
        // 이 클래스는 모든 웹사이트에서 공통적으로 사용하도록 Hanbit 프레임워크 네임스페이스안에 둔다
        if(!empty($user) && password_verify($password, $user[0][$this->passwordColumn])){
            //비밀번호로 보호된 내용 표시
            //이 함수는 세션 ID를 새로 바꿔주는 함수, 호출하면 해당 사용자에게 임의의 신규 세션 ID할당
            //로그인 하기 전 이미 세션 ID가 유출됐을 경우를 대비해 로그인 후 세션 ID를 교체 하는 것
            session_regenerate_id();
            $_SESSION['username'] = $username;
            //데이터베이스에 저장된 비밀번호를 읽을 때
            $_SESSION['password'] = $user[0][$this->passwordColumn];
            return true;
        } else {
            return false;
            //오류 메시지를 표시하고 사용자 로그아웃
        }
    }
    //기존에 생성된 세션 데이터가 있는지 먼저 검사한다., 세션변수가 없으면 거짓을 반환하며 사용자가 로그인하지 않은 상태임을 알 수있다.
    //마지막 안전 조치는 세션 ID변경 로그인 직후 세션 ID를 변경해 세션 유출 사고에 대비한다.

    public function isLoggedIn()
    {
        if(empty($_SESSION['username'])) {
            return false;
        }

        $user = $this->users->find($this->usernameColumn, strtolower($_SESSION['username']));

        if(!empty($user) && $user[0][$this->passwordColumn] === $_SESSION['password']){
            return true;
        } else {
            return false;
        }
    }
    //로그인 여부를 확인하고 사용자 정보 레코드를 배열로 반환
    public function getUser()
    {
        if($this->isLoggedIn()){
            return $this->users->find($this->usernameColumn, strtolower($_SESSION['username']))[0];
        } else {
            return false;
        }
    }
}
