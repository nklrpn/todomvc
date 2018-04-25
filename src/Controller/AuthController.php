<?php
namespace App\Controller;

use App\Storage\AuthStorage;

class AuthController extends Controller
{
    /**
     * @var AuthStorage
     */
    protected $storage;

    /**
     * @param AuthStorage $storage
     */
    public function __construct(AuthStorage $storage)
    {
        $this->storage = $storage;
        session_start();
    }

    /**
     * @param string $username
     * @return bool
     */
    public function isUsernameStored($username)
    {
        return $this->storage->isUsernameStored($username);
    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    public function register($username, $password)
    {
        $register = $this->storage->register($username, $password);
        
        if (!empty($register['errors'])) {
            return [
                'errors' => $register['errors'],
            ];
        }
        
        $_SESSION['user_id'] = $register['user_id'];
        $_SESSION['username'] = $username;

        return [
            'user_id' => $register['user_id'],
        ];
    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    public function login($username, $password)
    {
        $login = $this->storage->login($username, $password);
        
        if (!empty($login['errors'])) {
            return [
                'errors' => $login['errors'],
            ];
        }

        $_SESSION['user_id'] = $login['user_id'];
        $_SESSION['username'] = $username;
        
        return [
            'user_id' => $login['user_id'],
        ];
    }

    /**
     * @return void
     */
    public function logout()
    {
        session_destroy();
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        return isset($_SESSION['user_id']);
    }
}