<?php
namespace App\Test;

use PHPUnit\Framework\TestCase;
use App\Router;

class RouterTest extends TestCase
{
    private $router;

    protected function setUp()
    {
        $this->router = new Router();
    }

    protected function tearDown()
    {
        $this->router = null;
    }

    /**
     * @dataProvider uriProvider
     */
    public function testResolve($url, $route, $params)
    {
        $result = $this->router->resolve($url);
        
        $this->assertArrayHasKey('route', $result);
        $this->assertArrayHasKey('params', $result);
        
        $this->assertArraySubset($route, $result);
        $this->assertArraySubset($params, $result);
    }

    public function uriProvider()
    {
        $uri = 'http://localhost';
        
        return [
            [
                '/show',
                [
                    'route' => 'show',
                ],
                [
                    'params' => [],
                ]
            ],
            [
                '/add/text/New%20todo',
                [
                    'route' => 'add',
                ],
                [
                    'params' => [
                        'text' => 'New todo'
                    ],
                ]
            ],
            [
                '/destroy/id/6',
                [
                    'route' => 'destroy',
                ],
                [
                    'params' => [
                        'id' => 6
                    ],
                ]
            ],
            [
                '/edit/id/6/text/Changed%20todo',
                [
                    'route' => 'edit',
                ],
                [
                    'params' => [
                        'id' => 6,
                        'text' => 'Changed todo'
                    ],
                ]
            ],
            [
                '/toggle_state/id/6',
                [
                    'route' => 'toggle_state',
                ],
                [
                    'params' => [
                        'id' => 6,
                    ],
                ]
            ],
            [
                '/register/username/user1/password/******',
                [
                    'route' => 'register',
                ],
                [
                    'params' => [
                        'username' => 'user1',
                        'password' => '******',
                    ],
                ]
            ],
            [
                '/login/username/user1/password/******',
                [
                    'route' => 'login',
                ],
                [
                    'params' => [
                        'username' => 'user1',
                        'password' => '******',
                    ],
                ]
            ],
        ];
    }
}