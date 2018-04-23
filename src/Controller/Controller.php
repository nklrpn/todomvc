<?php
namespace App\Controller;

use App\Storage\StorageInterface;

class Controller
{
    protected $twig;
    
    /**
     * @var StorageInterface $storage
     */
    protected $storage;

    /**
     * @param $twig
     * @param StorageInterface $storage
     */
    public function __construct($twig, StorageInterface $storage)
    {
        $this->twig = $twig;
        $this->storage = $storage;
    }

    /**
     * @param string $template_name
     * @param array $params
     */
    public function render($template_name, $params = [])
    {
        $template = $this->twig->load($template_name);
        echo $template->render($params);
    }
}