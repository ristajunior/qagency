<?php

namespace App\QSSAPI\Request;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AuthorRequest extends RequestHandler
{
    /**
     * @var User
     */
    private $user;

    /**
     * AuthorRequest constructor.
     * @param string $url
     * @param SessionInterface $session
     */
    public function __construct(string $url, SessionInterface $session)
    {
        parent::__construct($url, $session);
        $this->url = $url . '/authors';
        $this->user = $this->session->get('user');
    }

    /**
     * Sends request to QSS API to create new author
     * @param string $author
     * @return bool
     */
    public function addAuthor(string $author)
    {
        $response = false;

        try {
            $apiResponse = $this->client->request('POST', $this->url, [
                'auth_bearer' => $this->user->getToken(),
                'body' => $author,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            if (200 === $apiResponse->getStatusCode()) {
                $response = true;
            }
        } catch (TransportExceptionInterface $e) {
            // log $e->getMessage();
        }

        return $response;
    }

    /**
     * Sends request to QSS API for getting all authors
     * @return string|null
     */
    public function getAllAuthors(): ?string
    {
        $response = null;

        try {
            $apiResponse = $this->client->request('GET', $this->url, [
                'auth_bearer' => $this->user->getToken(),
            ]);

            if (200 === $apiResponse->getStatusCode()) {
                $response = $apiResponse->getContent();
            }
        } catch (TransportExceptionInterface $e) {
            // log $e->getMessage();
        } catch (ClientExceptionInterface $e) {
            // log $e->getMessage();
        } catch (RedirectionExceptionInterface $e) {
            // log $e->getMessage();
        } catch (ServerExceptionInterface $e) {
            // log $e->getMessage();
        }

        return $response;
    }

    /**
     * Sends request to QSS API for getting single author
     * @param int $id
     * @return string|null
     */
    public function getAuthor(int $id): ?string
    {
        $response = null;

        try {
            $apiResponse = $this->client->request('GET', $this->url . '/' . $id, [
                'auth_bearer' => $this->user->getToken(),
            ]);

            if (200 === $apiResponse->getStatusCode()) {
                $response = $apiResponse->getContent();
            }
        } catch (TransportExceptionInterface $e) {
            // log $e->getMessage();
        } catch (ClientExceptionInterface $e) {
            // log $e->getMessage();
        } catch (RedirectionExceptionInterface $e) {
            // log $e->getMessage();
        } catch (ServerExceptionInterface $e) {
            // log $e->getMessage();
        }

        return $response;
    }

    /**
     * Sends request to QSS API for deleting an author
     * @param int $id
     * @return bool
     */
    public function deleteAuthor(int $id): bool
    {
        $response = false;

        try {
            $apiResponse = $this->client->request('DELETE', $this->url . '/' . $id, [
                'auth_bearer' => $this->user->getToken(),
            ]);

            if (204 === $apiResponse->getStatusCode()) {
                $response = true;
            }
        } catch (TransportExceptionInterface $e) {
            // log $e->getMessage();
        }

        return $response;
    }

    public function addAuthorFromConsole(string $author, string $token)
    {
        $response = false;

        try {
            $apiResponse = $this->client->request('POST', $this->url, [
                'auth_bearer' => $token,
                'body' => $author,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            if (200 === $apiResponse->getStatusCode()) {
                $response = true;
            }
        } catch (TransportExceptionInterface $e) {
            // log $e->getMessage();
        }

        return $response;
    }
}