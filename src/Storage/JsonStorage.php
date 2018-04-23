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
            throw new \Exception("File not found: {$filePath}");
        }
        
        $json = file_get_contents($filePath);
        $this->filePath = $filePath;

        $this->todos = json_decode($json, true);
    }

    /**
     * Get todos
     * @return array
     */
    public function getTodos() {
        return $this->todos;
    }

    /**
     * Add new todo
     * @param string $text
     * @return void
     */
    public function addTodo($text) {
        $todo = [
            'id' => date('YmdHis'),
            'text' => $text,
            'flag_active' => (bool) true,
        ];

        array_push($this->todos, $todo);

        $this->store();
    }

    /**
     * Destroy todo
     * @param string $id
     * @return void
     */
    public function destroyTodo($id) {
        $newTodos = array_filter($this->todos, function($item) use ($id) {
            return $item['id'] != $id;
        });

        if (count($newTodos) != count($this->todos)) {
            $this->todos = $newTodos;
            $this->store();
        }
    }

    /**
     * Toggle todo state: active or completed
     * @param string $id
     * @return void
     */
    public function toggleState($id) {
        $isToggled = false;

        foreach ($this->todos as &$todo) {
            if ($todo['id'] == $id) {
                $todo['flag_active'] = !$todo['flag_active'];
                $isToggled = true;
                break;
            }
        }

        if ($isToggled) {
            $this->store();
        }
    }

    /**
     * Edit todo
     * @param string $id
     * @param string $text
     * @return void
     */
    public function editTodo($id, $text) {
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
     * Store todos to file
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