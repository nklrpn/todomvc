<?php
namespace App\Storage;

class SessionStorage implements StorageInterface
{
    /**
     * @var array
     */
    protected $todos = [];

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
     * @return void
     */
    public function addTodo($text) {
        if (!$text) {
            return;
        }

        $todo = [
            'id' => date('YmdHis'),
            'text' => $text,
            'flagActive' => (bool) true,
        ];

        array_push($this->todos, $todo);

        $this->store();
    }

    /**
     * @param string $id
     * @return void
     */
    public function destroyTodo($id) {
        if (!$id) {
            return;
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
            $this->store();
        }
    }

    /**
     * @param string $id
     * @return void
     */
    public function toggleState($id) {
        if (!$id) {
            return;
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
            $this->store();
        }
    }

    /**
     * @param string $id
     * @param string $text
     * @return void
     */
    public function editTodo($id, $text) {
        if (!$id || !$text) {
            return;
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
            $this->store();
        }
    }

    /**
     * @return void
     */
    private function store()
    {
        if ($this->todos) {
            $_SESSION['todos'] = json_encode($this->todos);
        }
    }
}