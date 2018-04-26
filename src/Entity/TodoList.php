<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="lists")
 */
class TodoList
{
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="text")
     */
    private $json;

    /**
     * @var int
     * 
     * @ORM\Column(name="user_id", type="integer", nullable=false, unique=true)
     */
    private $userId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param string $json
     * @return void
     */
    public function setJson($json)
    {
        $this->json = $json;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}