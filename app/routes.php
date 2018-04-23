<?php
$routeParams = $router->resolve();

$id = (!empty($routeParams['params']['id'])) ? 
    $routeParams['params']['id'] :
    '';
$text = (!empty($routeParams['params']['text'])) ? 
    $routeParams['params']['text'] :
    '';

switch ($routeParams['route']) {
    case 'add':
        $controller->addTodo($text);
        break;
    case 'destroy':
        $controller->destroyTodo($id);
        break;
    case 'toggle_state':
        $controller->toggleState($id);
        break;
    case 'edit':
        $controller->editTodo($id, $text);
        break;
    case 'show':
        $todos = $controller->getTodos();
        $controller->render('todo_item.twig', ['todos' => $todos]);
        break;
    default:
        $todos = $controller->getTodos();
        $controller->render('index.twig', ['todos' => $todos]);
}
