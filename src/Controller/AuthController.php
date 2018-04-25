<?php
namespace App\Controller;

use App\Storage\AuthStorage;

class AuthController extends Controller
{
    /**
     * @var AuthStorage $storage
     */
    protected $storage;

    protected $session;

    /**
     * @param AuthStorage $storage
     * @param SessionController $session
     */
    public function __construct(AuthStorage $storage, SessionController $session)
    {
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
        $this->session->setUserId($login['user_id']);
        
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
        var_dump([
            __METHOD__ => session_status(),
            $this->session->isLogged(),
        ]);
        return isset($this->session) && $this->session->getUserId();
    }
}