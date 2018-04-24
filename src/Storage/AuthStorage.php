<?php
namespace App\Storage;

use Doctrine\ORM\EntityManager;
use App\Entity\User;

class AuthStorage
{
    /**
     * @var EntityManager
     */
    protected $em;

    protected $repository;

    /**
     * @var App\Entity\User
     */
    protected $obj;
    
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository('App\Entity\User');
        $this->obj = new User();
    }

    /**
     * @param string $username
     * @return bool
     */
    public function isUsernameStored($username)
    {
        $errors = [];
        
        if (!$username) {
            $errors[] = 'Username is empty!';
        }
        
        if (count($errors)) {
            return [
                'errors' => $errors,
            ];
        }

        $user = $this->repository->findOneBy([
            'username' => $username
        ]);
        
        if ($user) {
            return (bool) $user->getId();
        }

        return;
    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    public function register($username, $password)
    {
        $userId = 0;
        $errors = [];

        if (!$username) {
            $errors[] = 'Username cannot be empty!';
        }
        if (!$password) {
            $errors[] = 'Password cannot be empty!';
        }
        if (mb_strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 symbols!';
        }

        $isUsernameStored = $this->isUsernameStored($username);
        
        if (count($isUsernameStored['errors'])) {
            array_merge($errors, $isUsernameStored['errors']);
        }
        if ($isUsernameStored) {
            $errors[] = 'Username <' . $username . ' already exists!';
        }
        
        if (count($errors)) {
            return [
                'errors' => $errors,
            ];
        }
        
        $this->obj->setUsername($username);
        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->obj->setPassword($encryptedPassword);

        $this->em->persist($this->obj);
        $this->em->flush();

        $userId = $this->obj->getId();
        
        return [
            'user_id' => $userId,
        ];
    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    public function login($username, $password)
    {
        $userId = 0;
        $errors = [];

        if (!$username) {
            $errors[] = 'Username cannot be empty!';
        }
        
        if (count($errors)) {
            return [
                'errors' => $errors,
            ];
        }
        
        $user = $this->repository->findOneBy([
            'username' => $username
        ]);
        
        if (!$user || !password_verify($password, $user->getPassword())) {
            $errors[] = 'Username or password is incorrect.';
        }

        if (count($errors)) {
            return [
                'errors' => $errors,
            ];
        }
        
        $userId = $user->getId();
        
        return [
            'user_id' => $userId,
        ];
    }
}