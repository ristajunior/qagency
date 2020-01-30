<?php

namespace App\QSSAPI\Request;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TokenRequest extends RequestHandler
{
    /**
     * TokenRequest constructor.
     * @param string $url
     * @param SessionInterface $session
     */
    public function __construct(string $url, SessionInterface $session)
    {
        parent::__construct($url, $session);
        $this->url = $url . '/token';
    }

    /**
     * @param User $user
     * @return ResponseInterface|null
     */
    public function getTokenAndUserData(User $user): ?ResponseInterface
    {
        $response = null;

        try {
            $response = $this->client->request('POST', $this->url, [
                'json' => [
                    'email' => $user->getEmail(),
                    'password' => $user->getPassword()
                ],
            ]);

        } catch (TransportExceptionInterface $e) {
            // log $e->getMessage();
        }

        return $response;
    }
}