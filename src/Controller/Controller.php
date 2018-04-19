<?php
namespace App\Controller;

class Controller
{
    protected $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
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