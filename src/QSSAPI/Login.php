<?php

namespace App\QSSAPI;

use App\Builder\UserBuilder;
use App\Entity\User;
use App\QSSAPI\Request\TokenRequest;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Login
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var User
     */
    private $user;

    /**
     * @var UserBuilder
     */
    private $userBuilder;

    /**
     * Token request from QSS API
     * @var TokenRequest
     */
    private $tokenRequest;

    /**
     * Login constructor.
     * @param UserBuilder $userBuilder
     * @param User $user
     * @param SessionInterface $session
     * @param TokenRequest $tokenRequest
     */
    public function __construct(UserBuilder $userBuilder, User $user, SessionInterface $session, TokenRequest $tokenRequest)
    {
        $this->userBuilder = $userBuilder;
        $this->user = $user;
        $this->session = $session;
        $this->tokenRequest = $tokenRequest;
    }

    /**
     * Login user to session
     *
     * @param User $user
     *
     * @return bool
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function login(User $user): bool
    {
        $apiResponse = $this->tokenRequest->getTokenAndUserData($user);

        try {
            if (!$apiResponse || (200 !== $apiResponse->getStatusCode())) {
                return false;
            }
        } catch (TransportExceptionInterface $e) {
            // log $e->getMessage();
        }

        $content = json_decode($apiResponse->getContent());
        $user = $this->userBuilder->setUser($content->user, $content->token_key);
        $this->session->set('user', $user);

        return true;
    }

    /**
     * Logout user from session
     */
    public function logout(): void
    {
        $this->session->remove('user');
    }

    /**
     * @param User $user
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function loginConsole(User $user): ?string
    {
        $apiResponse = $this->tokenRequest->getTokenAndUserData($user);
        try {
            if (!$apiResponse || (200 !== $apiResponse->getStatusCode())) {
                return null;
            }
        } catch (TransportExceptionInterface $e) {
            // log $e->getMessage();
        }

        $content = json_decode($apiResponse->getContent());
        return $content->token_key;
    }
}