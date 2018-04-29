<?php
namespace App\Test;

use PHPUnit\Framework\TestCase;
use App\Storage\SessionStorage;

class SessionStorageTest extends TestCase
{
    private $storage;

    protected function setUp()
    {
        $this->storage = new SessionStorage();
    }

    protected function tearDown()
    {
        $this->storage = null;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddEmptyTodo()
    {
        $result = $this->storage->addTodo('');
    }
    
    public function testAddTodo()
    {
        $result = $this->storage->addTodo('New todo');
        $this->assertStringMatchesFormat('%d', $result);
        
        return $result;
    }
    
    /**
     * @depends testAddTodo
     */
    public function testGetTodos($id)
    {
        $result = $this->storage->getTodos();

        $this->assertCount(1, $result);

        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('text', $result[0]);
        $this->assertArrayHasKey('flagActive', $result[0]);
        
        $this->assertArraySubset(['id' => $id], $result[0]);
        $this->assertArraySubset(['text' => 'New todo'], $result[0]);
        $this->assertArraySubset(['flagActive' => '1'], $result[0]);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testToggleStateEmptyId()
    {
        $result = $this->storage->toggleState('');
    }
    
    /**
     * @depends testAddTodo
     */
    public function testToggleState($id)
    {
        $result = $this->storage->toggleState($id);
        $this->assertTrue($result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEditEmptyIdTodo()
    {
        $result = $this->storage->editTodo('', 'Newest todo');
    }

    /**
     * @depends testAddTodo
     * @expectedException InvalidArgumentException
     */
    public function testEditEmptyTextTodo($id)
    {
        $result = $this->storage->editTodo($id, '');
    }

    /**
     * @depends testAddTodo
     */
    public function testEditTodo($id)
    {
        $result = $this->storage->editTodo($id, 'Newest todo');
        $this->assertTrue($result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDestroyEmptyIdTodo()
    {
        $result = $this->storage->destroyTodo('');
    }
    
    /**
     * @depends testAddTodo
     */
    public function testDestroyTodo($id)
    {
        $result = $this->storage->destroyTodo($id);
        $this->assertTrue($result);
    }
}