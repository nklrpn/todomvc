<?php
namespace App\Controller;

class SessionController extends Controller
{
    const LIFETIME = 60*60*1;
    
    public function __construct()
    {
        session_start([
            'cookie_lifetime' => self::LIFETIME,
        ]);
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        if ($username) {
            $_SESSION['username'] = $username;
            setcookie('username', $username, self::LIFETIME);
        }
    }

    /**
     * @return void
     */
    public function getUsername()
    {
        $username = (!empty($_SESSION['username'])) ? 
            $_SESSION['username'] : 
            (!empty($_COOKIE['username'])) ? 
                $_COOKIE['username'] :
                '';

        return $username;
    }

    public function setUniqId()
    {
        $uniqId = uniqid();
        $_SESSION['uniqid'] = uniqId;
        setcookie('uniqid', $uniqId, self::LIFETIME);
    }

    /**
     * @return string
     */
    public function getUniqId()
    {
        $uniqid = (!empty($_SESSION['uniqid'])) ? 
            $_SESSION['uniqid'] : 
            (!empty($_COOKIE['uniqid'])) ?
                $_COOKIE['uniqid'] :
                '';

        return $uniqid;
    }

    public function __destruct()
    {
        session_destroy();
        setcookie('username', '', time()-3600);
    }
}