<?php
namespace App\Storage;

use App\Entity\TodoList;
use Doctrine\ORM\EntityManager;

class DatabaseStorage implements StorageInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    private $repo;

    /**
     * @var TodoList
     */
    private $obj;

    /**
     * @var int
     */
    private $userId;

    protected $repoList;
    
    /**
     * @var array
     */
    protected $todos = [];

    
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repo = $entityManager->getRepository('App\Entity\TodoList');
        
        $this->obj = new TodoList();

        $this->userId = $this->getUserId();
        $this->repoList = $this->getListByUserId();

        if (!$this->repoList) {
            $this->createList();
        } else {
            $this->todos = $this->getTodos();
        }
    }

    /** 
     * @return int
     */
    private function getUserId()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }
        
        return;
    }
    
    /**
     * @return array
     */
    private function getListByUserId()
    {
        if (!$this->userId) {
            return;
        }
        
        return $this->repo->findOneBy([
            'userId' => $this->userId
        ]);
    }

    /** 
     * @return void
     */
    private function createList()
    {
        if (!$this->repoList) {
            $this->obj->setJson(json_encode([]));
            $this->obj->setUserId($this->userId);

            $this->em->persist($this->obj);
            $this->em->flush();
        }
    }
    
    /**
     * @return void
     */
    private function updateTodos()
    {
        $this->repoList->setJson(json_encode($this->todos));

        $this->em->persist($this->repoList);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getTodos()
    {
        if (!$this->repoList) {
            return;
        }
        
        $json = $this->repoList->getJson();
        $this->todos = json_decode($json, true);

        return $this->todos;
    }

    /**
     * @param string $text
     * @return int
     */
    public function addTodo($text)
    {
        if (!$text) {
            throw new \Exception('Empty todo');
        }

        $todo = [
            'id' => date('ymdHis'),
            'text' => $text,
            'flagActive' => 1,
        ];

        array_push($this->todos, $todo);

        $this->updateTodos();
    }

    /**
     * @param int $id
     * @return void
     */
    public function destroyTodo($id)
    {
        if (!$id) {
            throw new \Exception('Missing id!');
        }

        $isDestroyed = false;
        
        foreach ($this->todos as $index => $todo) {
            if ($todo['id'] == $id) {
                unset($this->todos[$index]);
                $isDestroyed = true;
                break;
            }
        }

        if ($isDestroyed) {
            $this->updateTodos();
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function toggleState($id)
    {
        if (!$id) {
            throw new \Exception('Missing id!');
        }
        
        $isToggled = false;
        
        foreach ($this->todos as &$todo) {
            if ($todo['id'] == $id) {
                $todo['flagActive'] = !$todo['flagActive'];
                $isToggled = true;
                break;
            }
        }

        if ($isToggled) {
            $this->updateTodos();
        }
    }

    /**
     * @param int $id
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
        
        $isEdited = false;

        foreach ($this->todos as &$todo) {
            if ($todo['id'] == $id) {
                $todo['text'] = $text;
                $isEdited = true;
                break;
            }
        }

        if ($isEdited) {
            $this->updateTodos();
        }
    }
}