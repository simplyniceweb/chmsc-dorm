<?php

namespace helpers;

use Silex\Application;
use helpers\WebserviceUser;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{
    private $app;
    
    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function loadUserByUsername($username)
    {
        $query = $this->app['db']->createQueryBuilder()
            ->select("u.*")
            ->from('user', 'u')
            ->where("u.username = '$username'")
            ->setMaxResults(1);

        $userData = $this->app['db']->fetchAssoc($query);

        if ($userData) {
            $salt = "";
            $username = $userData['username'];
            $password = $userData['password'];
            $roles = explode(',', $userData['roles']);
            return new WebserviceUser($username, $password, $salt, $roles);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return WebserviceUser::class === $class;
    }
}
