<?php
namespace App\Controller;

use App\Storage\StorageInterface;

class StorageController extends Controller
{
    protected $twig;
    
    /**
     * @var StorageInterface
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
     * @return array
     */
    public function getTodos()
    {
        return $this->storage->getTodos();
    }

    /**
     * @param string $text
     * @return void
     */
    public function addTodo($text) 
    {
        $this->storage->addTodo($text);
    }

    /**
     * @param string $id
     * @return void
     */
    public function destroyTodo($id)
    {
        $this->storage->destroyTodo($id);
    }

    /**
     * @param string $id
     * @return void
     */
    public function toggleState($id)
    {
        $this->storage->toggleState($id);
    }

    /**
     * @param string $id
     * @param string $text
     * @return void
     */
    public function editTodo($id, $text)
    {
        $this->storage->editTodo($id, $text);
    }
}