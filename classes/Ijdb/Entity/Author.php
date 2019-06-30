<?php
namespace Ijdb\Entity;
//엔티티 클래스의 기본  기능은 데이터 조회다. 작성자명을 읽을 수 없는 작성자 엔티티 클래스는 쓸모가없다
//DatabaseTable 클래스는 배열 대신 Author 클래스 인스턴스를 반환해야 한다.
class Author {
    const EDIT_JOKES = 1;
	const DELETE_JOKES = 2;
	const LIST_CATEGORIES = 4;
	const EDIT_CATEGORIES = 8;
	const REMOVE_CATEGORIES = 16;
	const EDIT_USER_ACCESS = 32;
    public $id;
    public $name;
    public $email;
    public $password;
    private $jokesTable;


    public function __construct(\Hanbit\DatabaseTable $jokesTable)
    {
        $this->jokesTable = $jokesTable;
    }

    public function getJokes()
    {
        return $this->jokesTable->find('authorId', $this->id);
    }

    //유머글을 인수로 전달받아 authorId속성을 설정한 다음 데이터베이스에 저장한다.
    public function addJoke($joke) {
        $joke['authorId'] = $this->id;
        return $this->jokesTable->save($joke);
    }
    public function hasPermission($permission){
        
        return $this->permissions & $permission;
    }
}
