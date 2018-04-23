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
        $todos = $this->repository->findAll();
        return $todos;
    }

    /**
     * Add new todo
     * @param string $text
     * @return void
     */
    public function addTodo($text)
    {
        $this->obj->setText($text);

        $this->em->persist($this->obj);
        $this->em->flush();

        return $todo->getId();
    }

    /**
     * Destroy todo
     * @param string $id
     * @return void
     */
    public function destroyTodo($id)
    {
        $todo = $this->repository->findOneById($id);
        
        if (!$todo) {
            throw $this->createNotFoundException(
                'No todo found for id ' . $id
            );
        }

        $this->em->remove($todo);
        $this->em->flush();
    }

    /**
     * Toggle todo state: active or completed
     * @param string $id
     * @return void
     */
    public function toggleState($id)
    {
        $todo = $this->repository->findOneById($id);

        if (!$todo) {
            throw $this->createNotFoundException(
                'No todo found for id ' . $id
            );
        }

        $todo->setFlagActive();
        $this->em->flush();
    }

    /**
     * Edit todo
     * @param string $id
     * @param string $text
     * @return void
     */
    public function editTodo($id, $text)
    {
        $todo = $this->repository->findOneById($id);

        if (!$todo) {
            throw $this->createNotFoundException(
                'No todo found for id ' . $id
            );
        }

        $todo->setText($text);
        $this->em->flush();
    }
}