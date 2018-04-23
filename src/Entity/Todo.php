<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="todos")
 */
class Todo
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
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @var bool
     * 
     * @ORM\Column(name="flag_active", type="boolean", nullable=true)
     */
    private $flagActive = true;

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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return bool
     */
    public function getFlagActive()
    {
        return $this->flagActive;
    }

    /**
     * @return void
     */
    public function setFlagActive()
    {
        $this->flagActive = !$this->flagActive;
    }
}