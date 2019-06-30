<?php
namespace Ijdb;

class IjdbRoutes implements \Hanbit\Routes {
	private $authorsTable;
	private $jokesTable;
	private $categoriesTable;
	private $jokeCategoriesTable;
	private $authentication;

	public function __construct() {
		include __DIR__ . '/../../includes/DatabaseConnection.php';

		$this->jokesTable = new \Hanbit\DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this->authorsTable, &$this->jokeCategoriesTable]);
 		$this->authorsTable = new \Hanbit\DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->jokesTable]);
 		$this->categoriesTable = new \Hanbit\DatabaseTable($pdo, 'category', 'id', '\ijdb\Entity\Category', [&$this->jokesTable, &$this->jokeCategoriesTable]);
 		$this->jokeCategoriesTable = new \Hanbit\DatabaseTable($pdo, 'joke_category', 'categoryId');
		$this->authentication = new \Hanbit\Authentication($this->authorsTable, 'email', 'password');
	}

	public function checkPermission($permission): bool {
		$user = $this->authenticatin->getUser();

		if($user && $user->hasPermission($permission)){
			return true;
		} else {
			return false;
		}
	}
	public function getRoutes(): array {
		$jokeController = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorsTable, $this->categoriesTable, $this->authentication);
		$authorController = new \Ijdb\Controllers\Register($this->authorsTable);
		$loginController = new \Ijdb\Controllers\Login($this->authentication);
		$categoryController = new \Ijdb\Controllers\Category($this->categoriesTable);
        $routes = [
            'author/register' =>[
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'registrationForm'
                ],
                'POST' => [
                    'controller' => $authorController,
                    'action' => 'registerUser'
                ]

            ],
            'author/success' =>[
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'success'
                ]
            ],
            'joke/edit' =>[
                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'saveEdit'
                ],
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'edit'
                ],
                //접근 제한 페이지
                'login' => true
            ],
            'joke/delete' =>[
                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'delete'
                ],
                'login' => true
            ],
            'joke/list' =>[
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'list'
                ]
            ],
            'joke/home' => [
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'home'
                ]
            ],
            'login/error' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'error'
                ]
            ],
            'login' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'loginForm'
                ],
                'POST' => [
                    'controller' => $loginController,
                    'action' => 'processLogin'
                ]

            ],
            'login/success' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'success'
                ],
                'login' => true
            ],
            'logout' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'logout'
                ]
            ],
            'category/edit' => [
				'POST' => [
					'controller' => $categoryController,
					'action' => 'saveEdit'
				],
				'GET' => [
					'controller' => $categoryController,
					'action' => 'edit'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\Author::EDIT_CATEGORIES
			],
			'category/delete' => [
				'POST' => [
					'controller' => $categoryController,
					'action' => 'delete'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\Author::REMOVE_CATEGORIES
			],
			'category/list' => [
				'GET' => [
					'controller' => $categoryController,
					'action' => 'list'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\Author::LIST_CATEGORIES
			],
			'author/permissions' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'permissions'
				],
				'POST' => [
					'controller' => $authorController,
					'action' => 'savePermissions'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\Author::EDIT_USER_ACCESS
			],
			'author/list' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'list'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\Author::EDIT_USER_ACCESS
			],
        ];

        return $routes;
    }
    //웹 사이트를 사용할 Authentication 객체를 반환 이객체는 웹사이트 전용 인증 객체 Authentication객체를 EntryPoint 클래스 안에서 사용하지만
    // 웹사이트마다 테이블과 칼럼명이 다르므로 EntryPoint 안에서 생성할 수 없다.
    public function getAuthentication(): \Hanbit\Authentication
    {
        return $this->authentication;
    }
}
