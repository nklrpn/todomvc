<?php
$routeParams = $router->resolve();

$todo = [
    'id' => (!empty($routeParams['params']['id'])) ? filter_var($routeParams['params']['id'], FILTER_SANITIZE_NUMBER_INT) : '',
    'text' => (!empty($routeParams['params']['text'])) ? filter_var($routeParams['params']['text'], FILTER_SANITIZE_SPECIAL_CHARS) : '',
];

$user = [
    'isSubmitted' => isset($_POST['submit']),
    'username' => filter_input(INPUT_POST, 'username'),
    'password' => filter_input(INPUT_POST, 'password'),
];

switch ($routeParams['route']) {
    case 'add':
        $controller->addTodo($todo['text']);
        break;
    case 'destroy':
        $controller->destroyTodo($todo['id']);
        break;
    case 'toggle_state':
        $controller->toggleState($todo['id']);
        break;
    case 'edit':
        $controller->editTodo($todo['id'], $todo['text']);
        break;
    case 'show':
        $todos = $controller->getTodos();
        $controller->render('todo_item.twig', ['todos' => $todos]);
        break;
    case 'register':
        if ($auth->isLogged()) {
            header('Location: /');
            exit();
        }
        if ($user['isSubmitted']) {
            $register = $auth->register($user['username'], $user['password']);
        }
        if (!empty($register['user_id'])) {
            $_SESSION['user_id'] = $register['user_id'];
            $_SESSION['username'] = $user['username'];
            header('Location: /login');
            exit();
        }
        $params = (!empty($register['errors'])) ? ['errors' => $register['errors']] : [];
        $controller->render('register.twig', $params);
        break;
    case 'login':
        if ($auth->isLogged()) {
            header('Location: /');
            exit();
        }
        if ($user['isSubmitted']) {
            $login = $auth->login($user['username'], $user['password']);
        }
        if (!empty($login['user_id'])) {
            header('Location: /');
            exit();
        }
        $params = (!empty($login['errors'])) ? ['errors' => $login['errors']] : [];
        $controller->render('login.twig', $params);
        break;
    case 'logout':
        $auth->logout();
        header('Location: /');
        exit();
        break;
    default:
        if (!empty($_SESSION['username'])) {
            $params['username'] = $_SESSION['username'];
        }
        $params['todos'] = $controller->getTodos();
        $controller->render('todo_list.twig', $params);
}
