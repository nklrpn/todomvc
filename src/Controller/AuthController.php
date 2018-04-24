<?php
namespace App\Controller;

use App\Storage\AuthStorage;

class AuthController extends Controller
{
    protected $twig;
    
    /**
     * @var AuthStorage $storage
     */
    protected $storage;

    protected $session;

    /**
     * @param $twig
     * @param AuthStorage $storage
     */
    public function __construct($twig, AuthStorage $storage, $session)
    {
        $this->twig = $twig;
        $this->storage = $storage;
        $this->session = $session;
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
        
        $this->session->setUsername($username);
        
        return [
            'user_id' => $login['user_id'],
        ];
    }

    /**
     * @return void
     */
    public function logout()
    {
        unset($this->session);
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        return isset($this->session) && $this->session->getUsername();
    }
}