<?php
namespace App\Storage;

use App\Entity\Todo;
use Doctrine\ORM\EntityManager;

class DatabaseStorage implements StorageInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    private $repository;

    /**
     * @var Todo
     */
    private $obj;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository('App\Entity\Todo');
        $this->obj = new Todo();
    }

    /**
     * Get todos
     * @return array
     */
    public function getTodos()
    {
        return $this->repository->findAll();
    }

    /**
     * Add new todo
     * @param string $text
     * @return int
     */
    public function addTodo($text)
    {
        if (!$text) {
            throw new \Exception('Empty todo');
        }

        $this->obj->setText($text);

        $this->em->persist($this->obj);
        $this->em->flush();

        return $this->obj->getId();
    }

    /**
     * Destroy todo
     * @param int $id
     * @return void
     */
    public function destroyTodo($id)
    {
        if (!$id) {
            throw new \Exception('Missing id!');
        }
        
        $todo = $this->repository->findOneById($id);
        
        if (!$todo) {
            throw new \Exception('No todo found for id ' . $id);
        }

        $this->em->remove($todo);
        $this->em->flush();
    }

    /**
     * Toggle todo state: active or completed
     * @param int $id
     * @return void
     */
    public function toggleState($id)
    {
        if (!$id) {
            throw new \Exception('Missing id!');
        }
        
        $todo = $this->repository->findOneById($id);

        if (!$todo) {
            throw new \Exception('No todo found for id ' . $id);
        }

        $todo->setFlagActive();
        $this->em->flush();
    }

    /**
     * Edit todo
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
        
        $todo = $this->repository->findOneById($id);

        if (!$todo) {
            throw new \Exception('No todo found for id ' . $id);
        }

        $todo->setText($text);
        $this->em->flush();
    }
}