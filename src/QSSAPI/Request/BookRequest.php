<?php

namespace App\QSSAPI\Request;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BookRequest extends RequestHandler
{
    /**
     * @var User
     */
    private $user;

    /**
     * BookRequest constructor.
     * @param string $url
     * @param SessionInterface $session
     */
    public function __construct(string $url, SessionInterface $session)
    {
        parent::__construct($url, $session);
        $this->url = $url . '/books';
        $this->user = $this->session->get('user');
    }

    /**
     * Sends request to QSS API to create a new book
     * @param string $book
     * @return bool
     */
    public function addBook(string $book)
    {
        $response = false;

        try {
            $apiResponse = $this->client->request('POST', $this->url, [
                'auth_bearer' => $this->user->getToken(),
                'body' => $book,
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
     * Sends request to QSS API for deleting a book
     * @param int $id
     * @return bool
     */
    public function deleteBook(int $id): bool
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
}