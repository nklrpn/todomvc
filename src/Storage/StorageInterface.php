<?php
namespace App\Storage;

interface StorageInterface 
{
    /**
     * Get todos
     * @return array
     */
    public function getTodos();

    /**
     * Add new todo
     * @param string $text
     * @return string|bool Todo ID|false
     */
    public function addTodo($text);

    /**
     * Destroy todo
     * @param string $id
     * @return bool
     */
    public function destroyTodo($id);

    /**
     * Toggle todo state: active or completed
     * @param string $id
     * @return bool
     */
    public function toggleState($id);

    /**
     * Edit todo
     * @param string $id
     * @param string $text
     * @return bool
     */
    public function editTodo($id, $text);
}