<?php

namespace App\Builder;

use App\Entity\User;

class UserBuilder
{
    /**
     * @var User $user;
     */
    private $user;

    /**
     * UserBuilder constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $user
     * @param string $token
     * @return User
     */
    public function setUser($user, string $token): User
    {
        $this->user->setFirstName($user->first_name);
        $this->user->setLastName($user->last_name);
        $this->user->setEmail($user->email);
        $this->user->setToken($token);

        return $this->user;
    }
}