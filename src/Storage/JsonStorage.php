<?php
namespace App\Storage;

class JsonStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $filePath;
    
    /**
     * @var array
     */
    private $todos;

    public function __construct()
    {
        $filePath = __DIR__ . '/../../' . getenv('APP_JSONFILE_PATH');

        if (!$filePath || !file_exists($filePath)) {
            file_put_contents($filePath, '[]');
        }
        
        $json = file_get_contents($filePath);
        $this->filePath = $filePath;

        $this->todos = json_decode($json, true);
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
            file_put_contents(
                $this->filePath, 
                json_encode($this->todos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT)
            );
        }
    }
}