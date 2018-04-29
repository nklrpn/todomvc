<?php
namespace App\Storage;

class SessionStorage implements StorageInterface
{
    /**
     * @var array
     */
    protected $todos = [];

    /**
     * @return void
     */
    public function __construct()
    {
        if (isset($_SESSION['todos'])) {
            $this->todos = json_decode($_SESSION['todos'], true);
        }
    }

    /**
     * @return array
     */
    public function getTodos() {
        return $this->todos;
    }

    /**
     * @param string $text
     * @return string|bool Todo ID|false
     */
    public function addTodo($text) {
        if (!$text) {
            throw new \InvalidArgumentException('Missing text!');
        }

        $todo = [
            'id' => date('ymdHis'),
            'text' => $text,
            'flagActive' => (bool) true,
        ];

        array_push($this->todos, $todo);

        if ($this->store()) {
            return $todo['id'];
        }

        return false;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function destroyTodo($id) {
        if (!$id) {
            throw new \InvalidArgumentException('Missing id!');
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
            return $this->store();
        }

        return false;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function toggleState($id) {
        if (!$id) {
            throw new \InvalidArgumentException('Missing id!');
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
            return $this->store();
        }

        return false;
    }

    /**
     * @param string $id
     * @param string $text
     * @return bool
     */
    public function editTodo($id, $text) {
        if (!$id) {
            throw new \InvalidArgumentException('Missing id!');
        }
        if (!$text) {
            throw new \InvalidArgumentException('Missing text!');
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
            return $this->store();
        }

        return false;
    }

    /**
     * @return bool
     */
    private function store()
    {
        if (isset($this->todos) && is_array($this->todos)) {
            $_SESSION['todos'] = json_encode($this->todos);
            return true;
        }

        return false;
    }
}