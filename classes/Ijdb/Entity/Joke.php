<?php
namespace Ijdb\Entity;
//엔티티 클래스의 기본  기능은 데이터 조회다. 작성자명을 읽을 수 없는 작성자 엔티티 클래스는 쓸모가없다
//DatabaseTable 클래스는 배열 대신 Author 클래스 인스턴스를 반환해야 한다.
class Joke {
    public $id;
    public $authorId;
    public $jokedate;
    public $joketext;
    private $authorsTable;
    private $author;
    private $jokeCategoriesTable;

    public function __construct(\Hanbit\DatabaseTable $authorsTable, \Hanbit\DatabaseTable $jokeCategoriesTable)
    {
        $this->authorsTable = $authorsTable;
        $this->jokeCategoriesTable = $jokeCategoriesTable;
    }
    //현재 유머 글의 작성자를 반환한다.
    public function getAuthor()
    {
        //getAuthor()메서드에 캐싱 로직을 추가 1. 클래스 변수 author에 값이 있는지 확인 2. 없으면 데이터베이스에서 작성자 데이터를 가져와 저장 3. author변수를 반환
        if(empty($this->author)){
            $this->author = $this->authorsTable->findById($this->authorId);
        }
        return $this->author;
    }

    public function addCategory($categoryId){
        $jokeCat = ['jokeId' => $this->id, 'categoryId' => $categoryId];

        $this->jokeCategoriesTable->save($jokeCat);
    }
    //특정 글이 소속된 모든 카테고리를 찾은 다음 반복문을 실행해 $categoryId와 하나씩 비교한다.
    public function hasCategory($categoryId){
        $jokeCategories = $this->jokeCategoriesTable->find('jokeId', $this->id);

        foreach($jokeCategories as $jokeCategory){
            if($jokeCategory->categoryId == $categoryId){
                return true;
            }
        }
    }
    //특정 유머 글의 카테고리 정보를 모두 제거한다.
    public function clearCategories() {
        $this->jokeCategoriesTable->deleteWhere('jokeId', $this->id);
    }
}
