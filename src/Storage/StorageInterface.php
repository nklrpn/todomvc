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
     * @return void
     */
    public function addTodo($text);

    /**
     * Destroy todo
     * @param string $id
     * @return void
     */
    public function destroyTodo($id);

    /**
     * Toggle todo state: active or completed
     * @param string $id
     * @return void
     */
    public function toggleState($id);

    /**
     * Edit todo
     * @param string $id
     * @param string $text
     * @return void
     */
    public function editTodo($id, $text);
}