<?php
$routeParams = $router->resolve();

$id = (!empty($routeParams['params']['id'])) ? 
    $routeParams['params']['id'] : '';
$text = (!empty($routeParams['params']['text'])) ? 
    $routeParams['params']['text'] : '';

$username = (!empty($routeParams['params']['username'])) ? 
    $routeParams['params']['username'] : '';
$password = (!empty($routeParams['params']['password'])) ? 
    $routeParams['params']['password'] : '';

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
    case 'login':
        if ($username && $password) {
            $login = $auth->login($username, $password);
        }
        
        if (!empty($login['user_id'])) {
            header('Location: /', true);
        }

        $params = (!empty($login['errors'])) ? ['errors' => $login['errors']] : [];        
        $controller->render('login.twig', $params);
        break;
    case 'register':
        if ($username && $password) {
            $register = $auth->register($username, $password);
        }
        
        if (!empty($register['user_id'])) {
            header('Location: /', true);
        }
        
        $params = (!empty($login['errors'])) ? ['errors' => $login['errors']] : [];
        $controller->render('register.twig', $params);
        break;
    case 'logout':
        $auth->logout();
        header('Location: /', true);
        break;
    default:
        $username = ($auth->isLogged()) ? $session->getUsername() : '';
        
        $todos = $controller->getTodos();
        $controller->render('todo_list.twig', [
            'username' => $username,
            'todos' => $todos
        ]);
}
