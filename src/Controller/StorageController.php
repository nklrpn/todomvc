<?php
namespace App\Controller;

use App\Storage\StorageInterface;

class StorageController extends Controller
{
    protected $twig;
    
    /**
     * @var StorageInterface $storage
     */
    protected $storage;

    /**
     * @param $twig
     * @param StorageInterface $storage
     */
    public function __construct($twig, StorageInterface $storage)
    {
        $this->twig = $twig;
        $this->storage = $storage;
    }
    
    /**
     * Get todos
     * @return array
     */
    public function getTodos()
    {
        return $this->storage->getTodos();
    }

    /**
     * Add new todo
     * @param string $text
     * @return void
     */
    public function addTodo($text) 
    {
        if (!$text) {
            throw new \Exception('Missing value!');
        }

        $this->storage->addTodo($text);
    }

    /**
     * Destroy todo
     * @param string $id
     * @return void
     */
    public function destroyTodo($id)
    {
        if (!$id) {
            throw new \Exception('Missing id!');
        }

        $this->storage->destroyTodo($id);
    }

    /**
     * Toggle todo state: active or completed
     * @param string $id
     * @return void
     */
    public function toggleState($id)
    {
        if (!$id) {
            throw new \Exception('Missing id!');
        }
       
        $this->storage->toggleState($id);
    }

    /**
     * Edit todo
     * @param string $id
     * @param string $text
     * @return void
     */
    public function editTodo($id, $text)
    {
        if (!$id) {
            throw new \Exception('Missing id!');
        }
        if (!$text) {
            throw new \Exception('Missing value!');
        }
        
        $this->storage->editTodo($id, $text);
    }
}